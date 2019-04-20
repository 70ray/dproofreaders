<?php
include_once($relPath.'misc.inc');

class NonexistentUserProfileException extends Exception { }

class UserProfile
{
    private $table_row = array();

    // List of fields that when set for a user should never change
    private $immutable_fields = array(
        "id",
        "u_ref",
    );

    // Fields are assumed to be integers unless included here
    private $string_fields = array(
        "profilename",
    );

    private $all_fields = array(
        "id",
        "u_ref",
        "profilename",
        "i_res",
        "i_type",
        "i_layout",
        "i_toolbar",
        "i_statusbar",
        "i_newwin",
        "v_fnts",
        "v_fntf",
        "v_zoom",
        "v_tframe",
        "v_tlines",
        "v_tchars",
        "v_tscroll",
        "v_twrap",
        "h_fnts",
        "h_fntf",
        "h_zoom",
        "h_tframe",
        "h_tlines",
        "h_tchars",
        "h_tscroll",
        "h_twrap",
    );

    private $has_existing_db_row;
    private $modified_values = array();


    public function __construct($id=NULL)
    {
        if($id !== NULL)
        {
            $this->load($id);
            $this->has_existing_db_row = true;
        }
        else
        {
            $this->has_existing_db_row = false;
        }
    }

    private function check_valid($name)
    {
        if(!in_array($name, $this->all_fields))
        {
            throw new UnexpectedValueException(sprintf(
                _("%s is not a valid field"), $name)
            );
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

        $this->check_valid($name);
        $this->table_row[$name] = $value;
        $this->modified_values[] = $name;
    }

    public function __get($name)
    {
        $this->check_valid($name);
        return $this->table_row[$name];
    }

    public function __isset($name)
    {
        return isset($this->table_row[$name]);
    }

    private function load($id)
    {
        $escaped_id= (int)$id;

        $sql = "
            SELECT *
            FROM user_profiles
            WHERE id=$escaped_id
        ";

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
        {
            throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
        }
        elseif(mysqli_num_rows($result) == 0)
        {
            throw new NonexistentUserProfileException(sprintf(
                _('No user profile found with id = %s'), $id)
            );
        }
        $this->table_row = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
    }

    private function create_initial_db_row()
    {
        if(!isset($this->u_ref))
            throw new UnexpectedValueException("'u_ref' is a required field");

        $query = sprintf("
            INSERT INTO user_profiles
            SET
                u_ref = '%d'
        ",  (int)$this->u_ref
        );

        $result = mysqli_query(DPDatabase::get_connection(), $query);

        if(!$result)
        {
            throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
        }

        $this->id = mysqli_insert_id(DPDatabase::get_connection());

        // now load the row to pick up any default values
        $this->load($this->id);
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
            if(in_array($name, array("id", "u_ref")))
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
                UPDATE user_profiles
                SET $update_segment
                WHERE id = %d
            ",  (int)$this->id
            );

            $result = mysqli_query(DPDatabase::get_connection(), $query);

            if(!$result)
            {
                throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
            }
        }

        $this->modified_values = array();
    }

    // Load all profiles for a given u_ref
    // e.g. $user_profiles = UserProfiles::load_profiles_for_user($u_ref)
    public static function load_profiles_for_user($u_ref)
    {
        $sql = sprintf("
            SELECT id
            FROM user_profiles
            WHERE u_ref = %d
        ", (int)$u_ref
        );

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
        {
            throw new UnexpectedValueException(mysqli_error(DPDatabase::get_connection()));
        }
        $user_profiles = array();
        while($table_row = mysqli_fetch_assoc($result))
        {
            $user_profiles[] = new UserProfile($table_row["id"]);
        }

        mysqli_free_result($result);
        return $user_profiles;
    }

    public static function & get_user_profile($id)
    {
        static $profiles = array();
        if(array_key_exists($id, $profiles))
        {
            return $profiles[$id];
        }
        $profile = new UserProfile($id);
        $profiles[$id] =& $profile;
        return $profile;
    }
}

// vim: sw=4 ts=4 expandtab
