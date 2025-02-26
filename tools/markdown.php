<?php
require 'vendor/autoload.php';


function format_markdown($text)
{
    $Parsedown = new Parsedown();
    $html = $Parsedown->text($text);

    return $html;
}
