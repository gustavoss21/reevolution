<?php

namespace Routes;

use Config\RouterBase;

class Route extends RouterBase
{
    public $routes = [
        'GET' => [
            //WEB
            '/' => 'AppController@home',
            '/temas/{id}' => 'AppController@themeItem',

            //API
            '/temas' => 'ThemeController@getAllThemas',
            '/timeline' => 'ThemeController@timeline',
            '/media-de-status'=> 'ThemeController@statusAverage',
            '/time-without-study' => 'StageController@timeWithoutStudy',
            '/event-recommendation' => 'StageController@eventRecommendation',
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
