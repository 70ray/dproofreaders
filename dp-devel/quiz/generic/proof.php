<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

$page_id = get_enumerated_param($_REQUEST, 'type', NULL, $valid_page_ids);
$quiz_id = get_enumerated_param($_REQUEST, 'quiz_id', NULL, $valid_quiz_ids);
$utf8_site=!strcasecmp($charset,"UTF-8");

include "./data/qd_${page_id}.inc";

// Figure out what font to use
if ($user_is_logged_in)
{
    // Use the font prefs for the user's default interface layout, 
    // since they're more likely to have set those prefs
    global $userP;

    if ( $userP['i_layout']==1 )
    {
        $font_face_i = $userP['v_fntf'];
        $font_size_i = $userP['v_fnts'];    
    }
    else
    {
        $font_face_i = $userP['h_fntf'];
        $font_size_i = $userP['h_fnts'];
    }
    $font_face = $f_f[$font_face_i];
    $font_size = $f_s[$font_size_i];
}
else
{
    $font_face = NULL;
    $font_size = NULL;
}
    $font_settings = '';
    if ( $font_face != '' && $font_face != BROWSER_DEFAULT_STR )
    {
        $font_settings .= "font-family: $font_face;";
        $font_settings .= " ";
    }
    if ( $font_size != '' && $font_size != BROWSER_DEFAULT_STR )
    {
        $font_settings .= "font-size: $font_size;";
    }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="HTML Tidy, see www.w3.org">
<script type='text/javascript'>
s = "<?php
if("UTF-8" != strtoupper($charset))
{
    $solutions[0] = iconv("UTF-8", $charset, $solutions[0]);
}
if ($testing)
echo str_replace("\n",'\n',addslashes($solutions[0]));?>";
</script>
<title></title>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo "$charset";?>">
</head>
<body bgcolor='#ffffff' onload='top.initializeStuff(1)'>
<form action="./returnfeed.php?type=<?php echo $page_id; ?>&quiz_id=<?php echo $quiz_id;?>" target="right" method="post" name="editform" id="editform">
<textarea rows="12" cols="60" name="text_data" id="text_data" wrap="off" style='width:100%;
<?php 
    echo $font_settings;
?>
'>
<?php echo $ocr_text; ?>
</textarea> <p>
<input type="submit" value="<?php echo _("Check"); ?>">
<input type="reset" value="<?php echo _("Restart"); ?>"></form>

<a href='#' onclick="document.forms[0].elements['text_data'].value=s" accesskey='`'></a>

</body>
</html>
