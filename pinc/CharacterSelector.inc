<?php

class CharacterSelector
{
    public function __construct()
    {
    }

    public function echo_block()
    {
        // this is further constructed by javascript using data from the server
        echo "\n<div id='char-selector' class='float-left'></div>\n";
        echo "<div class='char_box float-left'><input type='text' class='big_text' id='large_char'></div>\n";
    }
}

// vim: sw=4 ts=4 expandtab
