<?php
// Invoking this file via include() causes the "image & text & buttons" parts
// of the proofing interface (i.e., everything but the "AEIOU+" panel)
// to be sent to the user.

include_once('proof_frame_std.inc');
include_once('proof_frame_enh.inc');
include_once($relPath.'User.inc'); // get_dp_user()

function echo_proof_frame( $ppage )
{
    $dp_user =& User::get_dp_user();

    $interface_type = $dp_user->i_type;
    switch ($interface_type)
    {
        case 0:
            // standard interface
            echo_proof_frame_std( $ppage );
            break;

        case 1:
            // enhanced interface
            echo_proof_frame_enh( $ppage );
            break;

        default:
            die( "unknown i_type: '$interface_type'" );
    }
}

// vim: sw=4 ts=4 expandtab
?>
