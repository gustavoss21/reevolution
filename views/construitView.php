<?php
require_once 'request.php';

$content = '';
$layout = '';
$title = "";
$urlBase = $_SERVER['SERVER_NAME'] . '/reevolution';
$static_links = [
    'top' => [
],
    'end' => []
];



function construictStatic(array $static_links,$urlbase) {
    $links_formated = ['top' => [], 'end' => []];

    foreach ($static_links as $position => $type_links ){//2
        foreach ($type_links as $type => $links) {//5
            foreach ($links as $link) {
                if ($type === 'style') {
                    $links_formated[$position][] = "<link rel='stylesheet' href='$urlbase/views/static/css/{$link}'>\n";
                } else if($type === 'script'){
                    $links_formated[$position][] = "<script src='$urlbase/views/static/js/{$link}'></script>\n";
                }else{
                    $links_formated[$position][] = $link;
                }
            }
        }
    }

    return $links_formated;
}

function setContext($buffer)
{
    // make $content available in the layout
    global $content;
    $content = $buffer;
}

ob_start("setContext");

require_once __DIR__ . '/' . $template . '.php';

ob_end_flush();

$content = $GLOBALS['content'];
$urlbase = 'HTTP://'. $_SERVER['SERVER_NAME'] . '/reevolution';
$static_links = construictStatic($static_links,$urlbase);


require_once $layout;
