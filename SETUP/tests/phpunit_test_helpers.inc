<?php

function create_test_user($username)
{
    // Attempt to load our test user, if it exists don't create it
    $sql = sprintf("
        SELECT username
        FROM users
        WHERE username = '%s'
    ", DPDatabase::escape($username));
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        $sql = sprintf("
            INSERT INTO users
            SET id = '%1\$s',
                real_name = '%1\$s',
                username = '%1\$s',
                email = '%1\$s@localhost'
        ", DPDatabase::escape($username));
        $result = DPDatabase::query($sql);
        if (!$result) {
            throw new Exception(sprintf("Unable to create test user %s", $username));
        }
    } else {
        mysqli_free_result($result);
    }
}

function delete_test_user($username)
{
    // remove the test user
    $sql = sprintf("
        DELETE FROM users
        WHERE id = '%s'
    ", DPDatabase::escape($username));
    $result = DPDatabase::query($sql);
    if (!$result) {
        throw new Exception(sprintf("Unable to delete test user %s", $username));
    }
}

function create_test_image_source($image_source)
{
    // Attempt to create the image source
    $sql = sprintf("
        SELECT code_name
        FROM image_sources
        WHERE code_name = '%s'
    ", DPDatabase::escape($image_source));
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        $sql = sprintf("
            INSERT INTO image_sources
            SET code_name = '%1\$s',
                display_name = '%1\$s',
                full_name = '%1\$s',
                info_page_visibility = 1,
                is_active = 1
        ", DPDatabase::escape($image_source));
        $result = DPDatabase::query($sql);
        if (!$result) {
            throw new Exception(sprintf("Unable to create test image source %s", $image_source));
        }
    } else {
        mysqli_free_result($result);
    }
}

function delete_test_image_source($image_source)
{
    // remove the test image source
    $sql = sprintf("
        DELETE FROM image_sources
        WHERE code_name = '%s'
    ", DPDatabase::escape($image_source));
    $result = DPDatabase::query($sql);
    if (!$result) {
        throw new Exception(sprintf("Unable to delete test image source %s", $image_source));
    }
}

function load_project_events($project)
{
    $sql = sprintf("
        SELECT *
        FROM project_events
        WHERE projectid = '%s'
        ORDER BY event_id
    ", DPDatabase::escape($project->projectid));
    $res = DPDatabase::query($sql);
    $events = [];
    while ($event = mysqli_fetch_assoc($res)) {
        $events[] = $event;
    }

    return $events;
}

// Delete remains of test projects that end up in other tables
function delete_test_project_remains($project)
{
    $projectid = $project->projectid;

    // character suites
    $project->set_charsuites([]);

    // project_holds
    $hold_states = $project->get_hold_states();
    if ($hold_states) {
        $project->remove_holds($hold_states);
    }

    // project pages table and directory
    if (does_project_page_table_exist($project->projectid)) {
        $project->delete();
    }

    // project_events
    $sql = sprintf("
        DELETE FROM project_events
        WHERE projectid  = '%s'
    ", DPDatabase::escape($projectid));
    DPDatabase::query($sql);

    // projects table
    $sql = sprintf("
        DELETE FROM projects
        WHERE projectid  = '%s'
    ", DPDatabase::escape($projectid));
    DPDatabase::query($sql);
}