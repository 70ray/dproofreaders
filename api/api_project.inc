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
include_once($relPath.'utils.inc'); // maybe_encode_array
include_once($relPath.'log_message.inc');
include_once('api_keydata.inc');

function project($project_id, $action)
{
    switch($action)
    {
    case 'listdata':
        $fields = array_get($_GET, 'fields', null);
        if(!$fields)
        {
            $fields = ['nameofwork', 'projectid'];
        }
        $data = get_project_data($project_id, $fields);
        maybe_decode_array($data);
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
        authorise_edit($project_id);
        $days = get_integer_param($_GET, 'days', 0, -1, 100);
        return project_good_word_suggestions($project_id, $days);

    case 'add_good_words':
        authorise_edit($project_id);
        $good_words = array_get($_POST, 'words', json_encode(array()));
        $good_words = json_decode($good_words);
        maybe_decode_array($good_words);
        project_add_good_words($project_id, $good_words);
        break;

    default:
        break;
    }
}

function authorise_edit($project_id)
{
    global $pguser;

    $project_manager = get_project_data($project_id, ['username'])['username'];
    if(($project_manager !== $pguser) && !user_is_proj_facilitator())
    {
        throw new Exception(_("It seems you are not authorised to perform this operation"));
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
        $this->projectid = $projectID;
        $this->proj_state = $proj_state;
        $this->imageID = $imageID;
    }

    const TEXT = 1;
    const INFO = 2;

    // send necessary data, doesn't matter if we send too much unless expensive to construct

    function page_data($dataset)
    {
        $data = array();
        if($dataset & self::TEXT)
        {
            $text = $this->lpage->get_text();
            $text = maybe_encode($text);
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

    function get_post_text()
    {
        global $utf8_site;

        $text_data = array_get($_POST, 'text-data', '');
        if(!$utf8_site)
        {
            $text_data = utf8_decode($text_data);
        }
        return $text_data;
    }

    function get_array($label)
    {
        $the_array = json_decode(array_get($_POST, $label, ''));
        maybe_decode_array($the_array);
        return $the_array;
    }

    function return_data($action)
    {
        global $pguser;

        switch($action) {
        case 'checkoutpage':
            $this->lpage->resume_saved_page();
            return $this->page_data(self::TEXT + self::INFO); // send everything
            break;
        case 'returnpage':
            $this->lpage->returnToRound();
            break;
        case 'reverttoorig':
            $this->lpage->saveAsInProgress($this->get_post_text());
            $this->lpage->revertToOriginal();
            return $this->page_data(self::TEXT); // send text and state
        case 'reverttolastsave':
            $this->lpage->revertToSaved();
            return $this->page_data(self::TEXT);
        case 'saveasinprogress':
            $this->lpage->saveAsInProgress($this->get_post_text());
            return $this->page_data(0); // just state
        case 'saveasdone':
            $this->lpage->saveAsDone($this->get_post_text());
            break;
        case 'reportbad':
            $reason = array_get($_REQUEST, 'reason', 0);
            $project_bad = $this->lpage->markAsBad($reason);
            return array('projectBad' => $project_bad);
        case 'wordcheck':
            global $aspell_temp_dir;
            if(!is_dir($aspell_temp_dir))
            {
                mkdir($aspell_temp_dir);
            }

            $aux_languages = $this->get_array('languages');
            return make_wordcheck_data($this->get_post_text(), $this->projectid, $this->imageID, $aux_languages);

        case 'save_wordcheck_event':
            save_wordcheck_event($this->projectid, $this->lpage->round->id, $this->imageID, $pguser, $this->get_array('accepted-words'), array());
            break;

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
    switch ($action)
    {
    case 'checkoutnextpage':
        return get_available_page($project_id, $proj_state);
    }
}

function maybe_encode($text)
{
    global $utf8_site;

    if(!$utf8_site)
    {
        $text = utf8_encode($text);
    }
    return $text;
}


define('WC_TEXT',   0);

class WcChunks {
    function __construct($text_data)
    {
        $this->text_data = $text_data;
        $this->chunks = array();
        $this->flags = array();
        $this->text_index = 0;
    }

    function add_plain($word_index)
    {
        $length = $word_index - $this->text_index;
        if($length)
        {
            $this->flags[] = WC_TEXT;
            $this->chunks[] = maybe_encode(substr($this->text_data, $this->text_index, $length));
            $this->text_index = $word_index;
        }
    }

    function add_word($level, $word)
    {
        $this->flags[] = $level;
        $this->chunks[] = maybe_encode($word);
        $this->text_index += strlen($word);
    }
}

function make_wordcheck_data($text_data, $projectid, $imagefile, $aux_languages)
{
    if(!$aux_languages)
    {
        $aux_languages = array_values(get_project_languages($projectid));
    }
    // make an array of chunks and flags:0 plain text 1,2,... bad word
    $wc_chunks = new WcChunks($text_data);
    
    list($bad_word_with_level, $languages, $messages) =
        get_bad_words_for_text( $text_data, $projectid, $imagefile, $aux_languages, array(), 'LEVELS' );
    $badWords = array_keys($bad_word_with_level);

    // find the index for each word in text
    foreach(get_all_words_in_text($text_data,TRUE) as $word_index => $word)
    {
        if(in_array($word, $badWords)) {
            // is there some plain text before bad word?
            $wc_chunks->add_plain($word_index);
            $wc_chunks->add_word($bad_word_with_level[$word], $word);
        }
    }
    $wc_chunks->add_plain(strlen($text_data));
    
    maybe_encode_array($languages);
    return array(
        'flags' => $wc_chunks->flags,
        'chunks' => $wc_chunks->chunks,
        'languages' => $languages,
        'messages' => $messages
    );
}

function project_good_word_suggestions($projectid, $days)
{
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
            $word = maybe_encode($word);
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
