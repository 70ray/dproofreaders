<?php
// The following variables must be defined by the file that includes this one:
// $relPath

include_once($relPath.'http_headers.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'User.inc'); // get_dp_user()

function echo_proof_frame_std( $ppage )
{
    $dp_user =& User::get_dp_user();

    if((int)$dp_user->i_layout == 1)
    {
        $rows_or_cols = "COLS='*,{$dp_user->v_tframe}%'";
    }
    else
    {
        $rows_or_cols = "ROWS='*,{$dp_user->h_tframe}%'";
    }

    $params = $ppage->urlencoded(TRUE);

    if ( ($dp_user->i_layout == '1' && $dp_user->v_tscroll==0)
        ||
         ($dp_user->i_layout == '0' && $dp_user->h_tscroll==0) )
    {
        $text_scrolling = "SCROLLING='no'";
    }
    else
    {
        $text_scrolling = "";
    }

    $margins = "marginwidth='2' marginheight='2'";

    // -------------------------------------

    slim_header_frameset($ppage->projectid());
    echo "
        <FRAMESET $rows_or_cols>
        <FRAME NAME='imageframe' SRC='image_frame_std.php?$params' $margins>
        <FRAME NAME='textframe' SRC='text_frame_std.php?$params' $margins $text_scrolling>
        </FRAMESET>
        <NOFRAMES>"._("Your browser is not frames capable!")."</NOFRAMES>
    ";
}

// vim: sw=4 ts=4 expandtab
