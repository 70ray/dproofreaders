<?php

// This file can be accessed directly in which case it needs appropriate
// HTML tags, and it can be include()d into other files, such as
// addproofer.php. We determine which is the case by checking if $relPath
// is set or not.
if(!isset($relPath))
{
    $relPath="./../pinc/";
    include_once($relPath.'base.inc');
    include_once($relPath.'theme.inc');

    if(!isset($_GET['embedded']))
        output_header(_("Privacy Statement"), FALSE);
}


$test_system_access_string = '';
if ($testing)
{
    $test_system_access_string = "<span class='test_warning'>";
    $test_system_access_string .= _('Because this is a testing site, developers with login access to this server will also have access to the Real Name you provide.');
    $test_system_access_string .= "</span>";
}

// The privacy statement is particularly important and thus needs to be
// translateable. To do this we use _() on every sentence (not on every
// paragraph to reduce the number of new translations required should
// any given sentence change). This makes the code more difficult to read
// though, particularly since many strings need $site_name replaced in them.
// To mitigate that, some wrapper functions are used to take straightforward
// _() strings and output them by first pre-processing all strings with
// sprintf(<string>, $site_name). sprintf will ignore unused arguments so
// this works fine for strings without $site_name substitutions.


echo "<h2>" . _('Privacy Statement') . "</h2>";

output_section(
    _('Usage of information'),
    array(
        _('When you register, %s will send a confirmation/introduction message to your e-mail address.'), 
        _('You may also receive optional e-mail alerts from our discussion forums and occasional notifications and feedback related to your proofreading activities.'),
        _('%1$s will also invite you by e-mail to vote in periodic Board Trustee elections and could contact you for site-related purposes authorized by %1$s management.'),
    )
);

output_section(
    _('Access to your information'),
    array(
        _('Only the %s site administrators will have access to the Real Name you provide.'),
        $test_system_access_string,
        _('If, in your forum profile, you do not opt to make your e-mail address accessible to all volunteers, it will be accessible only to our site administrators.'),
    ),
    array(
        _('During the various production phases, your User Name and your %s work may be viewable by other volunteers.'),
        _('In addition, volunteers delegated to an evaluation or mentoring role will be able to review the work performed under your User Name.'),
    ),
    array(
        _('Your User Name and any public information you provide in your forum profile will be accessible to other %s volunteers and to unregistered viewers.'),
        _('Your posts on our discussion forums will be viewable by other volunteers.'),
        _('Certain clearly-designated forums are also viewable by unregistered guests to the forums.'),
        _('Your user name and any information you post on the wiki are visible to both volunteers and unregistered viewers.'),
    ),
    array(
        _('Volunteers may opt not to display their Real or User Name in the credits of a publication while it is being worked on at %s and when it is distributed through Project Gutenberg or other publication channels.'),
    )
);

output_section(
    _('Tracking information'),
    array(
        _('%s tracks your User Name, the date your account was created and your last login date.'),
        _('This tracking information will be available to other volunteers.'),
        _('%s automatically receives and records information from your computer and browser, including your IP address.'),
        _('This is accessible to our administrative staff only.'),
    ),
    array(
        _("The system also tracks the number of pages you have completed, your best day ever, team memberships, proofreading roles, and the highest rank you've achieved."),
        _('In your %s site profile, you can control whether this information is viewable by everyone, registered users only, or yourself only.'),
    )
);

output_section(
    _('Sharing Information'),
    array(
        _('%s never makes commercial use of information entered on or for its website.'),
        _('It does not share volunteer information other than as indicated above.'),
    )
);

//---------------------------------------------------------------------------

function insert_site_name($sentence)
{
    global $site_name;
    return sprintf($sentence, $site_name);
}

function output_paragraph($sentences)
// Output a paragraph of sentences. Each sentence will have the first sprintf
// argument replaced with $site_name.
{
    echo "<p>";
    echo implode(" ", array_map("insert_site_name", $sentences));
    echo "</p>";
}

function output_section()
// Output a section of the privacy statement, including the section header
// and one or more paragraphs.
// The number of arguments this function accepts is variable, and is of the
// format ($header, $paragraph1, $paragraph2, ...) where each paragraph is
// an array of strings (ie: sentences).
{
    $arguments = func_get_args();
    $header = array_shift($arguments);
    echo "<h3>$header</h3>";
    foreach($arguments as $paragraph)
        output_paragraph($paragraph);
}

// vim: sw=4 ts=4 expandtab
