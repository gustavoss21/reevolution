<?php

namespace Routes;

use Config\RouterBase;

class Route extends RouterBase
{
    public $routes = [
        'GET' => [
            '/' => 'AppController@home',
            '/topics' => 'TopicController@index',
            '/topics/{id}' => 'TopicController@show',
            '/temas' => 'ThemeController@getAllThemas',
            '/temas/{id}' => 'AppController@themeItem',
            '/timeline' => 'ThemeController@timeline',
            '/media-de-status'=> 'ThemeController@statusAverage',
        ],
        'POST' => [
            '/topics' => 'TopicController@create',
        ],
        'PUT' => [
            '/topics/{id}' => 'TopicController@update',
        ],
        'DELETE' => [
            '/topics/{id}' => 'TopicController@delete',
        ],
    ];
}
