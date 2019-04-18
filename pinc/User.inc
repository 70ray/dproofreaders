<?php
include_once($relPath.'misc.inc');
include_once($relPath.'access_log.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'UserProfile.inc');

class NonexistentUserException extends Exception { }

class NonuniqueUserException extends Exception { }

class User
{
    private $table_row = array();

    // List of fields that when set for a user should never change
    private $immutable_fields = array(
        "id",
        "username",
        "u_id",
    );

    // Fields are assumed to be integers unless included here
    private $string_fields = array(
        "id",
        "real_name",
        "username",
        "email",
        "referrer",
        "http_referrer",
        "i_theme",
        "u_intlang",
    );

    private $all_fields = array(
        "id",
        "real_name",
        "username",
        "email",
        "referrer",
        "http_referrer",
        "i_theme",
        "u_intlang",
        "date_created",
        "last_login",
        "t_last_activity",
        "email_updates",
        "u_neigh",
        "u_align",
        "i_prefs",
        "i_pmdefault",
        "u_id",
        "u_profile",
        "u_privacy",
        "team_1",
        "team_2",
        "team_3",
    );

    private $has_existing_db_row;
    private $modified_values = array();

    public function __construct($username=NULL)
    {
        if($username !== NULL)
        {
            $this->load("username", $username);
            $this->has_existing_db_row = true;
        }
        else
        {
            // override database defaults
            $this->u_neigh = 10;
            $this->date_created = time();
            $this->has_existing_db_row = false;
        }
    }

    // The __set() and __get() methods allow access to user fields without
    // creating accessors for them all individually.
    // See the PHP docs for "magic methods".
    public function __set($name, $value)
    {
        if(isset($this->$name) && in_array($name, $this->immutable_fields))
        {
            throw new DomainException(sprintf(
                _("%s is an immutable field"), $name)
            );
        }

        if(in_array($name, $this->all_fields))
        {
            $this->table_row[$name] = $value;
            $this->modified_values[] = $name;
        }
        else
        {
            $profile =& UserProfile::get_user_profile($this->table_row['u_profile']);
            $profile->$name = $value;
        }
    }

    public function __get($name)
    {
        if(in_array($name, $this->all_fields))
        {
            return $this->table_row[$name];
        }
        else
        {
            $profile = UserProfile::get_user_profile($this->table_row['u_profile']);
//            $profile = new UserProfile($this->table_row['u_profile']);
            if($name == 'profile')
            {
                return $profile;
            }
            else
            {
                return $profile->$name;
            }
        }
    }

    public function __isset($name)
    {
        return isset($this->table_row[$name]);
    }

    private function load($field, $value, $strict=TRUE)
    {
        if(in_array($field, $this->string_fields))
        {
            $escaped_value = sprintf("'%s'", mysqli_real_escape_string(DPDatabase::get_connection(), $value));
        }
        else
        {
            $escaped_value = (int)$value;
        }

        $sql = "
            SELECT *
            FROM users
            WHERE $field=$escaped_value
        ";

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
        {
            throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
        }
        elseif(mysqli_num_rows($result) == 0)
        {
            throw new NonexistentUserException(sprintf(
                _('No user found with %1$s = %2$s'),
                    $field, $value)
            );
        }
        elseif(mysqli_num_rows($result) > 1)
        {
            throw new NonuniqueUserException(sprintf(
                _('Multiple users found with %1$s = %2$s'),
                    $field, $value)
            );
        }
        $this->table_row = mysqli_fetch_assoc($result);

        # MySQL does case-insensitive matches for strings, as well as
        # ignores trailing whitespace. If $strict is TRUE, and the field
        # is the username, require that the requested $username matches the
        # existing username in case and whitespace by doing a PHP string
        # compare.
        if($strict and $field == 'username' and $this->username != $value)
        {
            throw new NonexistentUserException(sprintf(
                _('No user found with %1$s = %2$s'),
                    $field, $value)
            );
        }

        mysqli_free_result($result);
    }

    private function create_initial_db_row()
    {
        if(!isset($this->username))
            throw new UnexpectedValueException("'username' is a required field");
        if(!isset($this->id))
            throw new UnexpectedValueException("'id' is a required field");

        $query = sprintf("
            INSERT INTO users
            SET
                username = '%s',
                id = '%s',
                date_created = $this->date_created
            ",  mysqli_real_escape_string(DPDatabase::get_connection(), $this->username),
                mysqli_real_escape_string(DPDatabase::get_connection(), $this->id)
        );

        $result = mysqli_query(DPDatabase::get_connection(), $query);

        if(!$result)
        {
            throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
        }

        // now load the row to pick up any default values
        $this->load("username", $this->username);
    }

    public function save()
    {
        if(!$this->has_existing_db_row)
            $this->create_initial_db_row();

        // Build the SQL updates for columns set in this object
        $column_assignments = array();
        foreach(array_unique($this->modified_values) as $name)
        {
            // These columns are handled in create_initial_db_row();
            if(in_array($name, array("id", "username", "date_created")))
                continue;

            if(in_array($name, $this->string_fields))
            {
                $column_assignments[] = sprintf("$name= '%s'", mysqli_real_escape_string(DPDatabase::get_connection(), $this->$name));
            }
            else
            {
                $column_assignments[] = "$name = " . (int)$this->$name;
            }
        }

        if($column_assignments)
        {
            $update_segment = implode(", ", $column_assignments);

            $query = sprintf("
                UPDATE users
                SET $update_segment
                WHERE username = '%s'
            ",  mysqli_real_escape_string(DPDatabase::get_connection(), $this->username)
            );

            $result = mysqli_query(DPDatabase::get_connection(), $query);

            if(!$result)
            {
                throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
            }
        }

        $this->modified_values = array();
        $profile = UserProfile::get_user_profile($this->table_row['u_profile']);
        $profile->save();
    }

    //
    // Activity access functions
    //
    // When we grant or revoke access to an activity we set a value in
    // the usersettings table as well as log the event to the access_log.
    // For historical reasons, Stage activities (proofreading rounds, etc)
    // include an .access suffix in the usersettings table (eg: P3.access)
    // whereas other activities do not (eg: manager). The $use_access_suffix
    // allows the caller to specify if the suffix should be included
    // without forcing the logic of what needs it into this class.

    public function grant_access($activity, $requester, $use_access_suffix=TRUE)
    {
        $this->_change_access("grant", $activity, $requester, $use_access_suffix);
    }

    public function revoke_access($activity, $requester, $use_access_suffix=TRUE)
    {
        $this->_change_access("revoke", $activity, $requester, $use_access_suffix);
    }

    public function request_access($activity, $use_access_suffix=TRUE)
    {
        $this->_change_access("request", $activity, 'n/a', $use_access_suffix);
    }

    public function deny_access($activity, $requester, $use_access_suffix=TRUE)
    {
        $this->_change_access("deny_request_for", $activity, $requester, $use_access_suffix);
    }

    private function _change_access($action_type, $activity, $requester, $use_access_suffix)
    {
        if($use_access_suffix)
            $suffix = '.access';
        else
            $suffix = '';

        $userSettings =& Settings::get_Settings($this->username);
        if($action_type == "request")
            $userSettings->set_value("$activity$suffix", "requested");
        elseif($action_type == "deny_request_for")
            $userSettings->set_value("$activity$suffix", NULL);
        else
            $userSettings->set_boolean("$activity$suffix", $action_type == 'grant');
        log_access_change($this->username, $requester, $activity, $action_type);
    }

    // static functions

    // Load a User record by u_id
    // e.g. $user = User::load_from_uid($u_id);
    public static function load_from_uid($u_id)
    {
        $user = new User();
        $user->load('u_id', $u_id);
        return $user;
    }

    // Load a User record by registration token (ie: the 'id' column)
    // e.g. $user = User::load_from_registration_token($id);
    public static function load_from_registration_token($id)
    {
        $user = new User();
        $user->load('id', $id);
        return $user;
    }

    // Static function to determine if the specified username is associated
    // with a valid user without the overhead of creating an object.
    public static function is_valid_user($username, $strict=TRUE)
    {
        $sql = sprintf("
            SELECT username
            FROM users
            WHERE username='%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $username));
        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(mysqli_num_rows($result) == 0)
        {
            $is_valid = FALSE;
        }
        else
        {
            # MySQL does case-insensitive matches for strings, as well as
            # ignores trailing whitespace. If $strict is TRUE, require that
            # the requested $username matches the existing username in case
            # and whitespace by doing a PHP string compare.
            if($strict)
            {
                list($existing_username) = mysqli_fetch_row($result);
                if($username != $existing_username)
                {
                    $is_valid = FALSE;
                }
                else
                {
                    $is_valid = TRUE;
                }
            }
            else
            {
                $is_valid = TRUE;
            }
        }

        mysqli_free_result($result);
        return $is_valid;
    }

    // Static function to return possible user referrer options
    public static function get_referrer_options()
    {
        return array(
            "blog" => _("Distributed Proofreaders Blog"),
            "twitter" => _("Twitter"),
            "facebook" => _("Facebook"),
            "friend" => _("Friend/Family"),
            "news" => _("News Article"),
            "pg" => _("Project Gutenberg"),
            "search" => _("Search Engine"),
            "ebook" => _("Within an E-book"),
            "other" => _("Other"),
        );
    }

    public static function & get_dp_user()
    {
        global $pguser;

        static $user = NULL;
        if(!$user)
        {
            $user = new User($pguser);
        }
        return $user;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$valid_username_chars_statement_for_reg_form  = _("(Valid characters are: a-z A-Z 0-9 @ - . space)");
$valid_username_chars_statement_for_elsewhere = _("(Valid characters are: a-z A-Z 0-9 @ - . _ space)");

function check_username( $username, $registering=FALSE )
// Check whether $username is a reasonable/acceptable User Name (ID).
// If it is, return an empty string.
// If not, return a string detailing the problem.
// (This is used at both registration and login.)
{
    $username_max_len = 25;
    // This is the length of the 'username' field in the 'users' table.

    if ( $username == '' )
    {
        $error = _("You did not supply a User Name (ID).");
        return $error;
    }

    if ( strlen($username) > $username_max_len )
    {
        $error = sprintf(_("Your User Name is too long.<br>(The maximum is %s characters.)"), $username_max_len);
        return $error;
    }

    // For new registrations, we disallow underscore (because it confuses the wiki software).
    // Elsewhere, we still have to allow underscore in usernames, for pre-existing users.
    if ($registering)
    {
        global $valid_username_chars_statement_for_reg_form;
        $bad_char_regex = "/[^-a-zA-Z0-9@. ]/";
        $valid_username_chars_statement = $valid_username_chars_statement_for_reg_form;
    }
    else
    {
        global $valid_username_chars_statement_for_elsewhere;
        $bad_char_regex = "/[^-a-zA-Z0-9@._ ]/";
        $valid_username_chars_statement = $valid_username_chars_statement_for_elsewhere;
    }
    if ( preg_match( $bad_char_regex, $username) )
    {
        $error = _("Your User Name contains invalid characters.")
            . "<br>"
            . $valid_username_chars_statement;
        return $error;
    }

    // In order to prevent one class of impersonations,
    // any space character in $username must have a non-space on either side.
    // Thus, no leading or trailing spaces, and no adjacent spaces.

    if ( trim($username) != $username )
    {
        $error = _("Your User Name has leading or trailing whitespace, which is not allowed.");
        return $error;
    }

    if (str_contains($username, "  "))
    {
        $error = _("Your User Name contains adjacent space characters, which is not allowed.");
        return $error;
    }

    return '';
}

// vim: sw=4 ts=4 expandtab
