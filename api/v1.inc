<?php
include_once('api_project.inc');
include_once('api_settings.inc');
include_once('api_constants.inc');
include_once('api_user.inc');
// DP API v1
function v1_project_action($params)
{
    check_login();
    $projectID = $params[0];
    $action = $params[1];
    return project($projectID, $action);
}

// post because not idempotent - checks out a new page every time
function v1_project_state_action($params)
{
    check_login();
    $project_id = $params[0];
    $proj_state = $params[1];
    $action = $params[2];
    return project_state_action($project_id, $proj_state, $action);
}

function v1_project_state_page_state_action($params)
{
    check_login();
    $projectID = $params[0];
    $proj_state = $params[1];
    $imageID = $params[2];
    $page_state = $params[3];
    $action = $params[4];
    $project_page = new ProjectPage($projectID, $proj_state, $imageID, $page_state);
    return $project_page->return_data($action);
//    return project_page_action($projectID, $proj_state, $imageID, $page_state, $action);
}

function v1_settings($params)
{
    check_login();
    $action = $params[0];
    return settings($action);
}

function v1_constants($params)
{
    check_login();
    return constants($params[0]);
}

function v1_user_action($params)
{
    check_login();
    $username = $params[0];
    $action = $params[1];
    return user_action($username, $action);
}

function v1_userisloggedin()
{
    global $user_is_logged_in;

    return array('loggedIn' => $user_is_logged_in);
}

function v1_useris($params)
{
    check_login();
    $user_role = $params[0];
    return user_is($user_role);
}

function v1_login($params)
{
    $userNM = $params[0];
    $userPW = array_get($_POST, 'userPW', '');
    return array('loggedIn' => loginUser($userNM, $userPW));
}

function check_login()
{
    global $user_is_logged_in;

    if(!$user_is_logged_in)
    {
        throw new Exception(_("You must be logged in to work here."));
    }
}
