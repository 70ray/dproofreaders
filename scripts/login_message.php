<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

echo "var noLogin = '", javascript_safe(_("It seems you are not logged in. Click 'OK' and you will be redirected to the login page.")), "';\n";
