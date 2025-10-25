<?php

namespace Routes;

use Config\RouterBase;

class Route extends RouterBase
{
    public $routes = [
        'GET' => [
            //WEB
            '/' => 'AppController@home',
            '/theme/{id}' => 'AppController@themeItem',

            //API
            '/temas' => 'ThemeController@getAllThemas',
            '/timeline' => 'ThemeController@timeline',
            '/status-averange'=> 'ThemeController@statusAverage',
            '/time-without-study' => 'StageController@timeWithoutStudy',
            '/new-event-init' => 'StageController@eventRecommendation',
            '/count-events-weekly' => 'StageController@eventsWeekly',
            '/averange-time-without-study' => 'StageController@averangeTimeWithoutStudy',
            '/more-time-without-study' => 'StageController@moreTimeWithoutStudy',
            '/more-priority-events' => 'StageController@morePriorityEvents',
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
