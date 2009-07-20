<?php
$relPath = '../../../pinc/';
include_once($relPath.'connect.inc');
new dbConnect();


$old_to_new = array(
	// Change R1+R2 to P1+P2
	'R1order'    => 'P1_order',
	'R2order'    => 'P2_order',

	// Rationalize setting names for pool-related sort orders.
	'ChPPorder'  => 'PP_ch_order',
	'PPorder'    => 'PP_av_order',
	'ChPPVorder' => 'PPV_ch_order',
	'PPVorder'   => 'PPV_av_order',

	// Rationalize setting names for stage-access
	'post_proof_verifier' => 'PPV.access',
);

foreach ( $old_to_new as $old => $new )
{
	echo "In 'setting' column, changing $old to $new ...\n";

	mysql_query("
		UPDATE usersettings
		SET setting='$new'
		WHERE setting='$old'
	") or die(mysql_error());
	echo "    ", mysql_affected_rows(), " rows affected.\n";
}

echo "\nDone!\n";
?>
