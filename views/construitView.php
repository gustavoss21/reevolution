<?php
require_once 'request.php';

$content = '';
$layout = '';
$array_data = $data;
$title = "";
$static_links = [
    'top' => [
],
    'end' => []
];



function construictStatic(array $static_links) {
    $links_formated = ['top' => [], 'end' => []];

    foreach ($static_links as $position => $type_links ){//2
        foreach ($type_links as $type => $links) {//5
            foreach ($links as $link) {
                if ($type === 'style') {
                    $links_formated['top'][] = "<link rel='stylesheet' href='views/static/css/{$link}'>\n";
                } else{
                    $links_formated['end'][] = "<script src='views/static/js/{$link}' defer></script>\n";
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
$static_links = construictStatic($static_links);


require_once $layout;
