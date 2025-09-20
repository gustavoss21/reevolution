<?php

namespace Routes;

use Config\RouterBase;

class Route extends RouterBase
{
    public $routes = [
        'GET' => [
            '/' => 'ThemeController@home',
            '/topics' => 'TopicController@index',
            '/topics/{id}' => 'TopicController@show',
            '/temas' => 'ThemaController@getAllThemas'
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
