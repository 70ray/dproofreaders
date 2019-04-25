<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // get_integer_param()
include_once('../includes/team.inc');

require_login();

$tid = get_integer_param($_GET, 'tid', null, 0, null);
$dp_user =& User::get_dp_user();
$leaving = false;

if($dp_user->team_1 == $tid)
{
    $dp_user->team_1 = 0;
    $leaving = true;
}
if($dp_user->team_2 == $tid)
{
    $dp_user->team_2 = 0;
    $leaving = true;
}
if($dp_user->team_3 == $tid)
{
    $dp_user->team_3 = 0;
    $leaving = true;
}

if($leaving)
{
    mysqli_query(DPDatabase::get_connection(), "UPDATE user_teams SET active_members = active_members-1 WHERE id='".$tid."'");
    $dp_user->save();
    dpsession_set_preferences_from_db();
    $title = _("Quit the Team");
    $desc = _("Quitting the team....");
    metarefresh(0,"../teams/tdetail.php?tid=".$tid."",$title,$desc);
}
else
{
    $title = _("Not a member");
    $desc = _("Unable to quit team....");
    metarefresh(3,"../teams/tdetail.php?tid=".$tid."",$title,$desc);
}

// vim: sw=4 ts=4 expandtab
