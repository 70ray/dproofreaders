<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');

function user_action($userNM, $action)
{
    switch($action)
    {
    case 'projects':
        return array('projects' => _get_projects_for_pm($userNM));
    default:
        break;
    }
}

function user_is($user_role)
{
    global $pguser;

    switch($user_role)
    {
    case 'project_facilitator':
        return array('name' => $pguser, 'project_facilitator' => user_is_proj_facilitator());
    }
}

function _get_projects_for_pm($pm)
 {
    global $utf8_site;

    $returnArray=array();
    $states = _get_project_states_in_order();
    $stateString = surround_and_join( $states, "'", "'", ',' );
    $where = "state IN ($stateString)";
    $collator = "FIELD(state,$stateString)";
    $query = sprintf("
        SELECT projectid, state, nameofwork
        FROM projects
        WHERE username='%s' AND $where
        ORDER BY $collator, nameofwork
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $pm));
//log_message($query);
    $res = try_query($query);
    while($ar = mysqli_fetch_assoc($res))
    {
        $projectid = $ar["projectid"];
        $name_of_work = $ar["nameofwork"];
        $state = $ar["state"];
        if(!$utf8_site)
        {
            $name_of_work = utf8_encode($name_of_work);
        }
        $returnArray[] = array($projectid, $name_of_work, $state);
    }
    return $returnArray;
}

function _get_project_states_in_order() {
    global $Round_for_round_id_;

    $projectStates = array();

    foreach($Round_for_round_id_ as $round_id => $round) {
        array_push($projectStates,$round->project_unavailable_state);
        array_push($projectStates,$round->project_waiting_state);
        array_push($projectStates,$round->project_bad_state);
        array_push($projectStates,$round->project_available_state);
        array_push($projectStates,$round->project_complete_state);
    }

    return $projectStates;
}

function loginUser($userNM, $userPW)
{
    if(!login_forum_user($userNM, $userPW))
    {
        return false;
    }
    // Look for user in 'users' table.
    $q = sprintf("
        SELECT * FROM users WHERE username='%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $userNM)
    );
    $u_res = try_query($q);
    $u_row = mysqli_fetch_assoc($u_res);
    if (!$u_row)
    {
        return false;
//        login_failure('reg_mismatch', $destination);
    }
    // -------------------------------------
    // The login is successful!

    // Note that phpbb_users.username and users.username are non-BINARY varchar,
    // so the SQL comparison "username='$userNM'" is evaluated case-insensitively.
    // This means that the user may have just logged in by typing a username
    // that's case-different from the username that they registered with.
    // That is, $userNM may be case-different from $u_row['username'].
    // (E.g., I registered as 'jmdyck', but I can login as 'JMDyck'.)
    //
    // However, some places in the PHP code do case-sensitive comparisons of
    // usernames (e.g., the code that determines whether the user is entitled
    // to pull a particular page out of DONE or IN-PROGRESS). For those places,
    // it's important that we always use the same form of the username. Following
    // the principle of least surprise, we use the form used at registration time,
    // i.e. the form stored in the users table.
    //
    $userNM = $u_row['username'];

    // Start the DP session.
    dpsession_begin( $userNM );
    return true;
}
// vim: sw=4 ts=4 expandtab
