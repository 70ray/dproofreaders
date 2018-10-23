<?php
// enables a button to be drawn either with an icon or with text
class ControlButton
{
    function __construct($handler, $label, $image, $class_name = '', $title = '')
    {
        $this->handler = $handler;
        if($title == '')
        {
            $title = $label;
        }
        $this->label = $label;
        $this->attr_title = attr_safe($title);
        $this->image = $image;
        $this->class_names = $class_name;
    }

    function draw($use_image)
    {
        if($use_image)
        {
            // middle align prevents extra space in button below image
            $caption = "<img src='gfx/$this->image' class='middle-align' alt='$this->attr_title'>";
            $this->class_names .= ' image-button'; // this removes padding
        }
        else
        {
            $caption = html_safe($this->label);
        }
        $class_string = '';
        if($this->class_names != '')
        {
            $class_string = "class='$this->class_names' ";
        }
        echo "<button type='button' onclick='$this->handler' {$class_string}title='$this->attr_title'>$caption</button>";
    }
}
