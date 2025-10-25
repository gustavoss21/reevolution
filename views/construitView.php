<?php
require_once 'request.php';

$content = '';
$layout = '';
$title = "";
$urlBase = 'http://localhost:5173/views/static/';
$statics = [];


//['arq' => '', 'dir', 'type' => '', 'tag' => '',position=>']
function construictStatic(array $statics) {
    
    $links = ['top'=>[],'end'=>[]];
    $urlbase = 'http://localhost:5173/views/static/';

    $style = function($item){
        $file = $item['dir'] .'css/'. $item['arq'];
        return "<link rel='stylesheet' href='$file'>\n";
    };
    
    $js = function ($item) {
        $file = $item['dir']. 'js/' . $item['arq'];
        return "<script type='{$item['type']}' src='$file'></script>\n";
    };

    $tag = function($link)use($style,$js){
        $tagi = $link['tag'];
        return $$tagi($link);
    };

    foreach ($statics as $static ){
        $static_links =  ['arq' => '', 'dir' => $urlbase, 'tag'=>'', 'type' => '', 'position' => 'top'];

        $static_links_fomated = array_merge($static_links, $static);
        
        $links[$static_links_fomated['position']][] = $tag($static_links_fomated);
    }

    return $links;
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

define('STATIC_LINKS', construictStatic($statics));


require_once $layout;
