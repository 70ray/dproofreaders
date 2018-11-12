<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'site_vars.php'); // $utf8_site
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc'); // project_states_text()
include_once($relPath.'LPage.inc');
include_once($relPath.'links.inc'); // private_message_link()
include_once($relPath.'forum_interface.inc'); // get_forum_user_id()
include_once($relPath.'wordcheck_engine.inc');
include_once('api_keydata.inc');

function project($project_id, $action)
{
    global $utf8_site, $pguser;

    switch($action)
    {
    case 'listdata':
        $fields = array_get($_GET, 'fields', null);
        if(!$fields)
        {
            $fields = ['nameofwork', 'projectid'];
        }
        $data = get_project_data($project_id, $fields);
        if(!$utf8_site)
        {
            array_walk($data, function(&$item) { $item = utf8_decode($item);} );
        }
        return array('project' => $data);

    case 'listpages':
        $fields = array_get($_GET, 'fields', null);
        if(!$fields)
        {
            $fields = ['fileid', 'image'];
        }
        return get_project_pages($project_id, $fields);

    case 'langdata':
        return get_langdata($project_id);

    case 'good_word_suggestions':
        $days = get_integer_param($_GET, 'days', 0, -1, 100);
        return project_good_word_suggestions($project_id, $days);

    case 'add_good_words':
        $project_manager = get_project_data($project_id, ['username'])['username'];
        if(($project_manager !== $pguser) && !user_is_proj_facilitator())
        {
            throw new Exception(_("It seems you are not authorised to perform this operation"));
        }
        $good_words = array_get($_POST, 'words', json_encode(array()));
        $good_words = json_decode($good_words);
        if(!$utf8_site)
        {
            array_walk($good_words, function(&$item) { $item = utf8_decode($item);} );
        }
        project_add_good_words($project_id, $good_words);
        break;

    default:
        break;
    }
}

function get_project_pages($projectid, $fields)
{
    $query_fields = implode(', ', $fields);
    $sql = "SELECT $query_fields FROM $projectid";
    $res = try_query($sql);
    return array('pages' => mysqli_fetch_all($res, MYSQLI_ASSOC));
}

class ProjectPage
{
    function __construct($projectID, $proj_state, $imageID, $page_state)
    {
        $this->lpage = get_indicated_LPage($projectID, $proj_state, $imageID, $page_state);
    }

    const TEXT = 1;
    const INFO = 2;

    // send necessary data, doesn't matter if we send too much unless expensive to construct

    function page_data($dataset)
    {
        global $utf8_site;

        $data = array();
        if($dataset & self::TEXT)
        {
            $text = $this->lpage->get_text();
            if(!$utf8_site)
            {
                $text = utf8_encode($text);
            }
            $data['text'] = $text;
            $data['imageID'] = $this->lpage->imagefile;
        }
        if($dataset & self::INFO)
        {
            $data['isFormattingRound'] = $this->lpage->is_formatting_round();
            $data['prevLinks'] = $this->p_links();
        }
        // always send this
        $data['pageState'] = $this->lpage->page_state;
        return $data;
    }

    function return_data($action)
    {
        global $pguser, $utf8_site;

        $text_data = array_get($_POST, 'text-data', '');
        if(!$utf8_site)
        {
            $text_data = utf8_decode($text_data);
        }

        switch($action) {
        case 'checkoutpage':
            $this->lpage->resume_saved_page($pguser);
            return $this->page_data(self::TEXT + self::INFO); // send everything
            break;
        case 'returnpage':
            $this->lpage->returnToRound($pguser);
            break;
        case 'reverttoorig':
            $this->lpage->saveAsInProgress($text_data, $pguser);
            $this->lpage->revertToOriginal();
            return $this->page_data(self::TEXT); // send text and state
        case 'reverttolastsave':
            $this->lpage->revertToSaved();
            return $this->page_data(self::TEXT);
        case 'saveasinprogress':
            $this->lpage->saveAsInProgress($text_data, $pguser);
            return $this->page_data(0); // just state
        case 'saveasdone':
            $this->lpage->saveAsDone($text_data, $pguser);
            break;
        case 'saveanddonext':
            $this->lpage->saveAsDone($text_data, $pguser);
            $this->lpage = get_available_page($project_id, $proj_state, $pguser);
            return $this->page_data(self::TEXT + self::INFO);
        case 'reportbad':
            $reason = array_get($_REQUEST, 'reason', 0);
            $project_bad = $this->lpage->markAsBad($pguser, $reason);
            return array('projectBad' => $project_bad);
        default:
            throw new Exception("$action not implemented");
            break;
        }
    }

    function p_links()
    {
        $round = $this->lpage->round;
        $other_round_ids = $round->other_rounds_with_visible_usernames;
        $round_links = array();
        foreach ($other_round_ids as $other_round_id)
        {
            $r_string = '';
            $other_round = get_Round_for_round_id($other_round_id);
            $username = $this->lpage->get_username_for_round( $other_round );

            $r_string .= "$other_round->id: ";
            // if no forum_user_id can't send message
            $user_id = get_forum_user_id($username);
            if ( $username == '' || $user_id == NULL )
            {
                // Grey, because it's not the link expected by the user.
                $r_string .= "<span style='color: #666;'>";
                if ($username == '')
                {
                    // e.g., the project might have skipped $other_round,
                    // or it could be a future round.
                    $r_string .= "<i>" . pgettext("no user", "none") . "</i>";
                }
                else
                    $r_string .= $username;
                $r_string .= "</span>";
            }
            else
            {
                $r_string .= private_message_link($username, "comments");
            }
            $round_links[] = $r_string;
        }
        return implode(", ", $round_links);
    }
}

function project_state_action($project_id, $proj_state, $action)
{
    global $pguser;

    switch ($action)
    {
    // returns page index (image file name) and changes page state to out
    // could be a stale link so validate
    case 'checkoutnextpage':
        $lpage = get_available_page($project_id, $proj_state, $pguser);
        return array('imageID' => $lpage->imagefile, 'pageState' => $lpage->page_state);
    }
}

function project_good_word_suggestions($projectid, $days)
{
    global $utf8_site;

    $goodFileObject = get_project_word_file($projectid, "good");
    // set the timeCutoff
    if($days == -1)
    {
        $timeCutoffActual = $goodFileObject->mod_time;
    }
    else if($days == 0)
    {
        $timeCutoffActual = 0;
    }
    else
    {
        $timeCutoffActual = ceil((time() - 24*60*60*$days)/100)*100;
    }
    // load suggestions since cutoff
    $suggestions = load_project_good_word_suggestions($projectid, $timeCutoffActual);

    // if there are no suggestions since the cutoff return empty array
    $suggestions_freq_occurences = array();
    // check that there are suggestions
    if(count($suggestions))
    {
        // load project good words
        $project_good_words = load_project_good_words($projectid);

        // load project bad words
        $project_bad_words = load_project_bad_words($projectid);

        // get the latest project text of all pages up to last possible round
        $last_possible_round = get_Round_for_round_number(MAX_NUM_PAGE_EDITING_ROUNDS);
        $pages_res = page_info_query($projectid, $last_possible_round->id, 'LE');
        $all_words_w_freq = get_distinct_words_in_text( get_page_texts( $pages_res ));

        // array to hold all words
        $all_suggestions = array();

        // parse the suggestions complex array
        // it is in the format: $suggestions[$round][$pagenum]=$wordsArray
        foreach( $suggestions as $round => $pageArray ) {
            foreach( $pageArray as $page => $words) {
                // add the words to the combined array too
                $all_suggestions = array_merge($all_suggestions, $words);
            }
        }

        // now, remove any words that are already on the project's good or bad words lists
        $all_suggestions = array_diff( $all_suggestions, array_merge($project_good_words, $project_bad_words) );

        // get the number of suggestion occurrences
        $all_suggestions_w_occurrences = generate_frequencies( $all_suggestions );

        // $all_suggestions doesn't have frequency info,
        // so start with the info in $all_words_w_freq,
        // and extract the items where the key matches a key in $all_suggestions.
        $all_suggestions_w_freq = array_intersect_key( $all_words_w_freq, array_flip( $all_suggestions ) );

        // multisort screws up all-numeric words so we need to preprocess first
        prep_numeric_keys_for_multisort( $all_suggestions_w_freq );

        // sort the list by frequency, then by word
        array_multisort( array_values( $all_suggestions_w_freq ), SORT_DESC, array_map( 'strtolower', array_keys( $all_suggestions_w_freq ) ), SORT_ASC, $all_suggestions_w_freq );

        // make an array of arrays [word, freq, occurences]
        foreach($all_suggestions_w_freq as $word => $freq)
        {
            $occurences = $all_suggestions_w_occurrences[$word];
            if(!$utf8_site)
            {
                $word = utf8_encode($word);
            }
            $suggestions_freq_occurences[] = array($word, $freq, $occurences);
        }
    }
    return array(
        'goodWordData' => $suggestions_freq_occurences,
        'timeCutoffActual' => $timeCutoffActual
    );
}

// arrays with numeric and string keys don't multisort correctly
// appending a space to the end of the numeric keys forces them
// to strings and multisort works correctly
function prep_numeric_keys_for_multisort(&$assocArray) {
    if(!is_array($assocArray)) return;
    foreach($assocArray as $key => $val) {
        if(!is_string($key)) {
            unset($assocArray[$key]);
            $assocArray["$key "]=$val;
        }
    }
}

function project_add_good_words($projectid, $good_words)
{
    $words = load_project_good_words($projectid);
    $words = array_merge($words, $good_words);
    save_project_good_words($projectid, $words);
}

// vim: sw=4 ts=4 expandtab
