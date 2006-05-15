<?
// DP includes
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');

// PHPBB includes (from the standard installation)
define('IN_PHPBB', true);
$phpbb_root_path = $forums_dir.'/';
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);

// include the custom PHPBB file
include_once($relPath . 'functions_insert_post.'.$phpEx);

// Which project?
$project_id = $_GET['project'];

// Get info about project
$proj_result = mysql_query("SELECT nameofwork, authorsname, topic_id, username, state FROM projects WHERE projectid='$project_id'");

$row = mysql_fetch_array($proj_result);

$topic_id = $row['topic_id'];

//Determine if there is an existing topic or not; if not, create one
if(($topic_id == "") || ($topic_id == 0))
{
        $nameofwork = $row['nameofwork'];
        $authorsname = $row['authorsname'];
        $proj_mgr = $row['username'];
	$state = $row['state'];

	// determine appropriate forum to create thread in
	$forum_id = get_forum_id_for_project_state($state);

        $post_subject = "\"".$nameofwork."\"    by ".$authorsname;
        $post_subject = str_replace('"','&quot;',$post_subject);

        $message =  "
This thread is for discussion specific to \"$nameofwork\" by $authorsname.

Please review the [url=$code_url/project.php?id=$project_id&detail_level=1]project comments[/url] before posting, as well as any posts below, as your question may already be answered there.

(This post is automatically generated.)
";

        // determine forums ID and signature preference of PM

        $id_result = mysql_query("SELECT user_id, user_attachsig FROM phpbb_users WHERE username = '".$proj_mgr."'");
        $id_row = mysql_fetch_array($id_result);

        $owner = $id_row['user_id'];
        $sig = $id_row['user_attachsig'];
        if ($sig == '') {$sig = 1;}

	// Don't post auto-posts as $pguser
	$user_ip = '7f000001'; //127.0.0.1

        // create the post
        $post_result =  insert_post(
                $message,
                $post_subject,
                $forum_id,  
                $owner,
                $proj_mgr,
                $sig);

        $topic_id = $post_result['topic_id'];

        //Update project_db with topic_id so it can be moved later
        $update_project = mysql_query("UPDATE projects SET topic_id=$topic_id WHERE projectid='$project_id'");


        // find out PM's preference about being signed up for notifications of replies to this thread;
        // can't use settings object, which would be for the user following the link to create the thread, 
        // which may not be the PM, so... go directly to the database table

        $signup_res = mysql_query("SELECT value FROM usersettings WHERE username = '".$proj_mgr."' AND setting = 'auto_proj_thread'" );
        if ($signup_res) {
             $signup_row = mysql_fetch_assoc($signup_res);
             $signup_pref = $signup_row['value'];
             $sign_PM_up = ($signup_pref == 'yes');
        } else {
             $sign_PM_up = false;
        }

        // if the PM wanted to be signed up for notifications, do so

        if ($sign_PM_up) {
             $do_signup = mysql_query("INSERT INTO phpbb_topics_watch (user_id, topic_id, notify_status)
                                     VALUES (". $owner . ", $topic_id, 0)");
        }

}

// By here, either we had a topic or we've just created one, so redirect to it

$redirect_url = "$forums_url/viewtopic.php?t=$topic_id";
header("Location: $redirect_url");
?>
