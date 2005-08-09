<?

// The 'queue_defns' table defines the various release queues that the autorelease code
// polls every hour in case any can release a new book for proofing.

// SPECIAL DAY queues are those that open on specific days only. They are defined in the 
// 'special_days' table. This script opens and closes these queues based upon the dates
// stored in those tables.

// (Originally, this script updated the project_selectors for Birthday/Otherday
// queues, hence the [now misleading] name of the script.)

$relPath='../pinc/';
include($relPath.'connect.inc');
new dbConnect();

$EOL = "\n<br>";
$testing_this_script=$_GET['testing'];

if ($testing_this_script)
{
    echo "<pre>", $EOL;
}


$today = date('md');
$tomorrow = date ('md', mktime (0,0,0,date("m")  ,date("d")+1,date("Y")));


// if run in last half hour of day, use tomorrow's dates  -
// this allows the queue to be redefined in time for the special books to come out
// at midnight or soon after
if (date('H') == "23") {
  if ((int)date('i') > 29) {
     $today = $tomorrow;
  }
}






// any SPECIAL queues to open today?

$check_month = substr($today, 0, 2);
$check_day = substr($today,2);

$specials_query = "SELECT spec_code FROM special_days WHERE open_month = $check_month and open_day = $check_day and enable = 1";
echo $specials_query, $EOL;
$open_these = mysql_query($specials_query) or die(mysql_error());
$numrows = mysql_num_rows($open_these);
$rownum = 0;
while ($rownum < $numrows) 
{
	$to_open=mysql_fetch_assoc($open_these);
	$selector = "%special_code = '".$to_open['spec_code']."'%";
	$update_query =
		"UPDATE queue_defns SET enabled = 1 WHERE  project_selector like \"$selector\"";

	if ($testing_this_script)
	    {
        	echo $update_query, $EOL;
	    }
	else
	    {	
        	echo $update_query, $EOL;
	        mysql_query($update_query) or die(mysql_error());
	    }

	$rownum++;
}


// any SPECIAL queues to close today?

$specials_query = "SELECT spec_code FROM special_days WHERE close_month = $check_month and close_day = $check_day and enable = 1";
echo $specials_query, $EOL;
$close_these = mysql_query($specials_query) or die(mysql_error());
$numrows = mysql_num_rows($close_these);
$rownum = 0;
while ($rownum < $numrows) 
{
	$to_close=mysql_fetch_assoc($close_these);
	$selector = "%special_code = '".$to_close['spec_code']."'%";
	$update_query =
		"UPDATE queue_defns SET enabled = 0 WHERE  project_selector like \"$selector\"";

	if ($testing_this_script)
	    {
        	echo $update_query, $EOL;
	    }
	else
	    {	
        	echo $update_query, $EOL;
	        mysql_query($update_query) or die(mysql_error());
	    }

	$rownum++;

}


if ($testing_this_script)
{
    echo "</pre>", $EOL;
}

?>
