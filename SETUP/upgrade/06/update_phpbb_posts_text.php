<?php
// A script to change project URLs in phpbb posts.
// (from c/tools/proofers/projects.php?project=foo to c/project.php?id=foo)

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

$dry_run = 0;

$search_string = '/tools/proofers/projects.php\?project=';

if (TRUE)
{
    $query = "
        SELECT phpbb_posts.post_id, post_text
        FROM phpbb_posts JOIN phpbb_posts_text USING (post_id)
        WHERE
            forum_id IN (
                $waiting_projects_forum_idx,
                $projects_forum_idx,
                $pp_projects_forum_idx,
                $posted_projects_forum_idx
            )
            AND
            INSTR(post_text,'$search_string')
    ";
}
else
{
    $query = "
        SELECT post_id, post_text
        FROM phpbb_posts_text
        WHERE
            INSTR(post_text,'$search_string')
    ";
}

$res = mysqli_query(DPDatabase::get_connection(), $query) or die(mysqli_error(DPDatabase::get_connection()));

$n_posts_found = mysqli_num_rows($res);
echo "Found $n_posts_found posts containing the search string.\n";
if ( $n_posts_found == 0 )
{
    echo "\nDone!\n";
    return;
}

if ($dry_run)
{
    echo "Examining them...\n";
}
else
{
    echo "Attempting to change them...\n";
}

while ( list($post_id, $post_text) = mysqli_fetch_row($res) )
{
    if ($dry_run)
    {
        echo "---------------------------------------------------------------------\n";
        echo "id = $post_id\n";
        echo "text:\n";
        echo $post_text;
        echo "\n";
    }

    $new_post_text = preg_replace(
        '#/tools/proofers/projects.php\?project=(projectID\w+)(&amp;|&)proofing=1#',
        '/project.php?id=\1&amp;detail_level=1',
        $post_text );

    if ($new_post_text == $post_text)
    {
        echo "Odd: for post_id=$post_id, preg_replace has no effect.\n";
        continue;
    }

    if ($dry_run) continue;

    $new_post_text = mysqli_real_escape_string(DPDatabase::get_connection(), $new_post_text);
    mysqli_query(DPDatabase::get_connection(), "
        UPDATE phpbb_posts_text
        SET post_text='$new_post_text'
        WHERE post_id=$post_id
    ");
    $n = mysqli_affected_rows(DPDatabase::get_connection());
    if ($n != 1)
    {
        echo "Odd: for post_id=$post_id, UPDATE affects $n rows\n";
    }
}

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
