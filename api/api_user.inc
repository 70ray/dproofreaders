<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
/*include_once($relPath.'site_vars.php'); // $utf8_site
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc'); // project_states_text()
include_once($relPath.'LPage.inc');
include_once($relPath.'links.inc'); // private_message_link()
include_once($relPath.'forum_interface.inc'); // get_forum_user_id()
include_once('api_keydata.inc');*/

function user($username, $action)
{
    switch($action)
    {
    case 'projects':
        return array('projects' => _get_projects_for_pm($username));
    default:
        break;
    }
}

function identify($user_role)
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
// vim: sw=4 ts=4 expandtab
