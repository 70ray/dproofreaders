<?php
$relPath="../pinc/";

function get_keydata()
{
    // space is separator, 0 means no character in this position
    // this data would normally be obtained from the database
    // each character can be multibyte
    // we are cheating by having this file encoded in utf8 when not utf8 site
    $keyboards = array(
    array(
        'code' => "EU",
        'upper' => "À Â Ä Æ È É Ê Ë Î Ï Ô Ö Ù Û Ü 0 Ç 0",
        'lower' => "à â ä æ è é ê ë î ï ô ö ù û ü ß ç ÿ"
        ),
    array(
        'code' =>'¶',
        'upper' => "¶ ° º ·",
        'lower' => "§ 0 £"
        )
    );
    return $keyboards;
}