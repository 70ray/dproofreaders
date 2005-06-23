<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'page_tally.inc');
include_once('../includes/team.inc');
include_once('../includes/member.inc');
$db_Connection=new dbConnect();

$tally_name = array_get( $_GET, 'tally_name', null );

$id = array_get( $_GET, 'id', '' );
if (empty($id)) {
	echo "mdetail.php: missing or empty 'id' parameter";
	exit;
}

$result = mysql_query("
	SELECT *
	FROM users
	WHERE u_id = '$id'
");

if (mysql_num_rows($result) == 0)
{
	echo "mdetail.php: no user with u_id='$id'";
	exit;
}

$curMbr = mysql_fetch_assoc($result);

// Two possible ways to refer to the subject user:
$quoted_username = "'" . $curMbr['username'] . "'";
$number_u_id = "#" . $curMbr['u_id'];

if ($curMbr['u_privacy'] == PRIVACY_ANONYMOUS && $curMbr['username'] != $pguser) {
	$user_referent = $number_u_id;
	// Note that this doesn't reveal anything;
	// the requestor already knows the subject's u_id,
	// because it was included in the request.
	$brushoff = _("This user has requested to remain anonymous.");
} elseif ($curMbr['u_privacy'] == PRIVACY_PRIVATE && !isset($pguser)) {
	$user_referent = $number_u_id;
	$brushoff = _("This user has requested their statistics remain private.");
} else {
	$user_referent = $quoted_username;
	$brushoff = NULL;
}

$desc = sprintf( _("Details for user %s"), $user_referent );
theme($desc, "header");

echo "<br><center>";

echo "<h1>$desc</h1>";

if (is_null($brushoff)) {
	showMbrInformation( $curMbr, $tally_name );
} else {
	echo "<p>$brushoff</p>";
}

echo "</center>";
theme("", "footer");
?>
