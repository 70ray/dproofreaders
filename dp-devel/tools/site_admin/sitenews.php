<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');

if ( !(user_is_a_sitemanager() or user_is_site_news_editor()) )
{
    echo "You are not authorized to use this form.";
    exit;
}

if (isset($_GET['news_page'])) {
    $news_page = $_GET['news_page'];
    $type_result = mysql_query("SELECT * FROM news_pages WHERE news_page_id = '$news_page'");
    if ($news_type_row = mysql_fetch_assoc($type_result)) {
        $news_type = _($news_type_row['news_type']);
        $last_modified = strftime(_("%A, %B %e, %Y"), $news_type_row['modifieddate']);
        theme("Site News Update for ".$news_type, "header");
        echo "<br>";
        echo "<a href='sitenews.php'>"._("Site News Central")."</a><br>";
    } else {
        echo _("Error").": <b>".$news_page."</b> "._("Unknown news_page specified, exiting.");
        exit();
    }
} else {

    theme("Site News Central", "header");
    $type_result = mysql_query("SELECT * FROM news_pages WHERE 1 = 1 order by news_type");

    if ($type_result) {

        echo "<h1>"._("Site News Central")."</h1>";
        echo "<br><br><font size = +1><ul>";
        while ($news_type_row = mysql_fetch_assoc($type_result)) {
            $news_page_id = $news_type_row['news_page_id'];
            $news_type = _($news_type_row['news_type']);
            $last_modified = strftime(_("%A, %B %e, %Y"), $news_type_row['modifieddate']);
            echo "<li>"._("Edit Site News for ")."<a href='sitenews.php?news_page=".$news_page_id."'>".
                $news_type."</a> "._("Last modified : ").$last_modified."<br><br>";
        }
        echo "</ul></font>";
    }
    theme('','footer');
    exit();
}

handle_any_requested_db_updates( $news_page );
show_item_editor( $news_page, $news_type );
show_all_news_items_for_page( $news_page, $news_type, $last_modified );

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function handle_any_requested_db_updates( $news_page )
{
        // Save a new site news item
        if (isset($_GET['action']) && $_GET['action'] == "add") {
            $content = strip_tags($_POST['content'], '<a><b><i><u><font><img>');
            $content = nl2br($content);
            $date_posted = time();
            $insert_news = mysql_query("
                INSERT INTO news_items
                SET
                    id           = NULL,
                    news_page_id = '$news_page',
                    status       = 'current',
                    date_posted  = '$date_posted',
                    content      = '$content'
            ");
            // by default, new items go at the top
            $update_news = mysql_query("
                UPDATE news_items SET ordering = id WHERE id = LAST_INSERT_ID()
            ");
            news_change_made($news_page);
        }
        // Delete a specific site news item
        elseif (isset($_GET['action']) && $_GET['action'] == "delete") {
            $item_id = $_GET['item_id'];
            $result = mysql_query("DELETE FROM news_items WHERE id=$item_id");
        }
        // Display a specific site news item
        elseif (isset($_GET['action']) && $_GET['action'] == "display") {
            $item_id = $_GET['item_id'];
            $result = mysql_query("UPDATE news_items SET status = 'current' WHERE id=$item_id");
            news_change_made($news_page);
        }
        // Hide a specific site news item
        elseif (isset($_GET['action']) && $_GET['action'] == "hide") {
            $item_id = $_GET['item_id'];
            $result = mysql_query("UPDATE news_items SET status = 'recent' WHERE id=$item_id");
            news_change_made($news_page);
        }
        // Archive a specific site news item
        elseif (isset($_GET['action']) && $_GET['action'] == "archive") {
            $item_id = $_GET['item_id'];
            $result = mysql_query("UPDATE news_items SET status = 'archived' WHERE id=$item_id");
        }
        // Unarchive a specific site news item
        elseif (isset($_GET['action']) && $_GET['action'] == "unarchive") {
            $item_id = $_GET['item_id'];
            $result = mysql_query("UPDATE news_items SET status = 'recent' WHERE id=$item_id");
        }
        // Move a specific site news item higher in the display list
        elseif (isset($_GET['action']) && $_GET['action'] == "moveup") {
            $item_id = $_GET['item_id'];
            move_news_item ($news_page, $item_id, 'up');
            news_change_made($news_page);
        }
        // Move a specific site news item lower in the display list
        elseif (isset($_GET['action']) && $_GET['action'] == "movedown") {
            $item_id = $_GET['item_id'];
            move_news_item ($news_page, $item_id, 'down');
            news_change_made($news_page);
        }
        // Save an update to a specific site news item
        elseif (isset($_GET['action']) && $_GET['action'] == "edit_update") {
            $content = $_POST['content'];
            $content = strip_tags($_POST['content'], '<a><b><i><u><font><img>');
            $content = nl2br($content);
            $item_id = $_POST['item_id'];
            $result = mysql_query("UPDATE news_items SET content='$content' WHERE id=$item_id");
            $result = mysql_query("SELECT status FROM news_items WHERE id=$item_id");
            $row = mysql_fetch_assoc($result);
            $visible_change_made = ($row['status'] == 'current');
            if ($visible_change_made) {news_change_made($news_page);}
        }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_item_editor( $news_page, $news_type )
// Show a form:
// -- to edit the text of an existing item (if requested), or
// -- to compose a new item (otherwise).
{
    if (isset($_GET['action']) && $_GET['action'] == "edit") {
        $item_id = $_GET['item_id'];
        $result = mysql_query("SELECT * FROM news_items WHERE id=$item_id");
        $content = mysql_result($result,0,"content");
        $action = "edit_update";
        $submit_query = "Edit Site News Item for ".$news_type;
    } else {
        $item_id = "";
        $content = "";
        $action = "add";
        $submit_query = "Add Site News Item for ".$news_type;
    }

    echo "<form action='sitenews.php?news_page=$news_page&action=$action' method='post'>";
    echo "<center><textarea name='content' cols=50 rows=5>$content</textarea><br><input type='submit' value='$submit_query' name='submit'></center><br><br>";
    echo "<input type='hidden' name='item_id' value='$item_id'></form>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_all_news_items_for_page( $news_page, $news_type, $last_modified )
{
    // three categories:
    // 1) current (currently displayed on page every time)
    // 2) recent (displayed on "Recent News", and one shown as Random)
    // 3) archived (not visible to users at all, saved for later use or historical interest)

    $result = mysql_query("
        SELECT *
        FROM news_items
        WHERE news_page_id = '$news_page' AND status != 'archived'
        ORDER BY status ASC, ordering DESC
    ");

    if (mysql_numrows($result) > 0) {

        $first_recent = 1;
        $first_vis = 1;

        echo "<font size=+2><b>"._("Fixed News Items for ").$news_type.
            "</b></font>&nbsp;&nbsp; ("._("Last modified: ").$last_modified.")<hr><br><br>";

        echo _("All of these items are shown every time the page is loaded. Most important and recent news items go here, where they are guaranteed to be displayed.")."<br><br>";

        while($news_item = mysql_fetch_array($result)) {
            $date_posted = strftime(_("%A, %B %e, %Y"),$news_item['date_posted']);
            $status = $news_item['status'];
            $base_url = "[<a href='sitenews.php?news_page=$news_page&item_id=".$news_item['id']."&action=";
            if ($status == 'current') {
                echo $base_url."hide'>"._("Make Random")."</a>]&nbsp;";
                if ($first_vis == 1) {
                    $first_vis = 0;
                } else {
                    echo $base_url."moveup'>"._("Move Higher")."</a>]&nbsp;";
                }
                echo $base_url."movedown'>"._("Move Lower")."</a>]&nbsp;";
            } else {
                if ($first_recent == 1) {
                    echo "<br><br><font size=+2><b>"._("Random News Items for ").$news_type.
                        _(" (Also appear as 'Recent News')")."</b></font><hr><br><br>";
                    echo _("This is the pool of available random news items for this page. Every time the page is loaded, a randomly selected one of these items is displayed.")."<br><br>";
                    $first_recent = 0;
                }
                echo $base_url."display'>Make Fixed</a>]&nbsp;";
                echo $base_url."archive'>Archive Item</a>]&nbsp;";
            }
            echo $base_url."edit'>Edit</a>]&nbsp;";
            echo $base_url."delete'>Delete</a>]&nbsp; -- ($date_posted)<br><br>";
            echo $news_item['content']."<br><br>";
        }
    }

    $result = mysql_query("
        SELECT *
        FROM news_items
        WHERE news_page_id = '$news_page' AND status = 'archived'
        ORDER BY id DESC;
    ");

    if (mysql_numrows($result) > 0) {

        echo "<font size=+2><b>"._("Archived News Items for ").$news_type.
            _(" (Only visible on this page)")."</b></font><hr><br><br>";
        echo _("Items here are not visible anywhere, and can be safely stored here until they become current again.")."<br><br>";
        while($news_item = mysql_fetch_array($result)) {
            $date_posted = strftime(_("%A, %B %e, %Y"),$news_item['date_posted']);
            $base_url = "[<a href='sitenews.php?news_page=$news_page&item_id=".$news_item['id']."&action=";
            echo $base_url."unarchive'>Unarchive Item</a>]&nbsp;";
            echo $base_url."edit'>Edit</a>]&nbsp;";
            echo $base_url."delete'>Delete</a>]&nbsp; -- ($date_posted)<br><br>";
            echo $news_item['content']."<br><br>";
        }
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function news_change_made ($news_page) {
    $date_changed = time();
    $result = mysql_query("
            UPDATE news_pages SET modifieddate = $date_changed WHERE news_page_id = '$news_page'
    ");
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function move_news_item ($news_page_id, $id_of_item_to_move, $direction) {

    $result = mysql_query("
        SELECT * FROM news_items
        WHERE news_page_id = '$news_page_id' AND
            status = 'current'
        ORDER BY ordering
    ");

    $i = 1 ;
    while ($news_item = mysql_fetch_assoc($result)) {
        $curr_id = $news_item['id'];
        $update_query = mysql_query("
            UPDATE news_items SET ordering = $i WHERE id = $curr_id
        ");
        if (intval($curr_id) == intval($id_of_item_to_move)) {$old_pos = $i;}
        $i++;
    }

    if (isset($old_pos)) {
        if ($direction == 'up') {
            $result = mysql_query("
                UPDATE news_items SET ordering = $old_pos
                WHERE news_page_id = '$news_page_id' AND status = 'current' AND ordering = ($old_pos + 1)
            ");
            $result = mysql_query("
                UPDATE news_items SET ordering = $old_pos + 1 WHERE id = $id_of_item_to_move
            ");
        } else {
            $result = mysql_query("
                UPDATE news_items SET ordering = $old_pos
                WHERE news_page_id = '$news_page_id' AND status = 'current' AND ordering = ($old_pos - 1)
            ");
            $result = mysql_query("
                UPDATE news_items SET ordering = $old_pos - 1 WHERE id = $id_of_item_to_move
            ");
        }
    }
}




theme("", "footer");

// vim: sw=4 ts=4 expandtab
?>
