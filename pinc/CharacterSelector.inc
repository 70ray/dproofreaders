<?php

class CharacterSelector
{
    public function __construct()
    {
        // this could be in database but needs to be translatable
        $this->char_tooltips = [
            '¶' => _('pilcrow'),
            '°' => _('degree'),
            'º' => _('masculine ordinal'),
            '·' => _('mid-dot'),
        ];
    }

    public function echo_block()
    {
// this data would normally be obtained from the database
        $keyboards = array(
        array(
            'code' => "EU",
            'upper' => "À Â Ä Æ È É Ê Ë",
            'lower' => "à â ä æ è é ê ë"
//            'upper' => "\xc3\x80 \xc3\x81 \xc3\x82 \xc3\x80 \xc3\x81 \xc3\x82 \xc3\x80 \xc3\x81 \xc3\x82 \xc3\x80 \xc3\x81 \xc3\x82 \xc3\x80 \xc3\x81 \xc3\x82 \xc3\x80 \xc3\x81 \xc3\x82 \xc3\x80 \xc3\x81 \xc3\x82 \xc3\x80 \xc3\x81 Y\xcc\x86 ",
//            'lower' => "\xc3\xa0 \xc3\xa1 \xc3\xa2 \xc3\xa0 \xc3\xa1 \xc3\xa2 \xc3\xa0 \xc3\xa1 \xc3\xa2 \xc3\xa0 \xc3\xa1 \xc3\xa2 \xc3\xa0 \xc3\xa1 \xc3\xa2 \xc3\xa0 \xc3\xa1 \xc3\xa2 \xc3\xa0 \xc3\xa1 \xc3\xa2 \xc3\xa0 \xc3\xa1 y? "
            ),
/*        array(
            'code' => "aß",
            'upper' => "? ? G ? ? ? ? T ? ? ? ? ? ? ? ? ? S 0 ? ? F ? ? O",
            'lower' => "\xce\xb1 \xce\xb2 \xce\xb3 \xce\xb4 \xce\xb5 \xce\xb6 \xce\xb7"
            ),
        array(
            'code' => "\xe1\xbc\x88",
            'upper' => "\xe1\xbc\x88 \xe1\xbc\x89 \xe1\xbc\x8a \xe1\xbc\x8b",
            'lower' => "\xe1\xbc\x80 \xe1\xbc\x81 \xe1\xbc\x82 \xe1\xbc\x83"
            ),
        array(
            'code' => "??",
            'upper' => "?? ??",
            'lower' => "?? ??"
            ),*/
        array(
            'code' =>'¶',
            'upper' => "¶ ° º ·",
            'lower' => "§ £"
            )
        );

        $initial_code = "EU";

        echo "<script>initialCode = '$initial_code';</script>";

        echo "\n<div id='char-selector'>";

        $selector_row = "";
// also need a means for installing a subset of available keyboards and enabling a default
        foreach($keyboards as $keyboard)
        {
            $code = $keyboard["code"];
            $selector_row .= "<input type='button' id='id_$code' class='selector_button' onclick='picker.enableBoard(\"$code\")' value='$code'>";
            $this->echo_row("upper $code", $keyboard['upper']);
            $this->echo_row("lower $code", $keyboard['lower']);
        }
        echo "<div id='selector_row'>$selector_row</div>";
        echo "<div class='char_box'><input type='text' class='big_text' id='large_char'></div>\n";
        echo "</div>\n";
    }

    public function echo_row($class, $char_row)
    {
        $array_chars = explode(" ", $char_row);
        echo "<div class='$class'>";
        foreach($array_chars as $char)
        {
            if("0" != $char)
            {
                $actions = " onmouseover='picker.showChar(\"$char\")' onclick='top.insertCharacter(\"$char\")' value='$char'";
                if(array_key_exists($char, $this->char_tooltips))
                {
                    $actions .= " title='{$this->char_tooltips[$char]}'";
                }
            }
            else
            {
                $actions = "value=' '";
            }
            echo "<input type='button' class='picker'$actions>";
        }
        echo "</div>\n";
    }
}

// vim: sw=4 ts=4 expandtab
