<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//this module sets users inactive who have not been active on the site in 6 months

    $old_date = time() - 15768000; // 6 months ago.

    $result = mysql_query ("UPDATE `users` SET active = 'no' WHERE t_last_activity < $old_date AND active ='yes'");
    $numrows = mysql_num_rows($result);

    echo "inactivate_users.php set $numrows users who have not been active for 6 months as inactive";
?>
