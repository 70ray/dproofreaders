<?php
$relPath="../pinc/";
include_once($relPath.'languages.inc');

function get_langdata($project_id)
{
    // space is separator, 0 means no character in this position
    // this data would normally be obtained from the database
    // each character can be multibyte
    // we are cheating by having this file encoded in utf8 when not utf8 site
    // otherwise would have to utf8_encode the strings
    $keyboards = array(
    array(
        'code' => "DF",
        'upper' => "À Â Ä Æ È É Ê Ë Î Ï Ô Ö Ù Û Ü 0 Ç 0",
        'lower' => "à â ä æ è é ê ë î ï ô ö ù û ü ß ç ÿ"
        ),
    array(
        'code' =>'¶',
        'upper' => "¶ ° º ·",
        'lower' => "§ 0 £"
        )
    );

    $project = new Project($project_id);
    $lang_code = proj_lang_code($project->language, "primary");
    $dir = lang_direction($lang_code);

    return array('keyboards' => $keyboards, 'langdir' => $dir);
}
