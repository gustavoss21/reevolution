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
            '/timeline' => 'ThemeController@timeline'
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
