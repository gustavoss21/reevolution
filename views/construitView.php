<?php
require_once 'request.php';

$content = '';
$layout = '';
$title = "";
$urlBase = 'http://localhost:5173/views/static/';

function setContext($buffer)
{
    // make $content available in the layout
    global $content;
    $content = $buffer;
}

ob_start("setContext");

require_once __DIR__ . '/page/' . $template . '.php';

ob_end_flush();

$content = $GLOBALS['content'];

require_once $layout;
