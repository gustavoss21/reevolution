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
            '/status-averange' => 'ThemeController@statusAverage',
            '/time-without-study' => 'StageController@timeWithoutStudy',
            '/new-event-init' => 'StageController@eventRecommendation',
            '/count-events-weekly' => 'StageController@eventsWeekly',
            '/averange-time-without-study' => 'StageController@averangeTimeWithoutStudy',
            '/date-time-without-study' => 'StageController@dateTimeWithoutStudy',
            '/more-time-without-study-event' => 'StageController@moreTimeWithoutStudyEvent',
            '/more-priority-events' => 'StageController@morePriorityEvents',
            '/total-quantity-each-tatus' => 'StageController@totalQuantityEachStatus',
            '/match-event/{name}'=> 'ThemeController@matchEvent',
            '/match-event-topic/{name}'=> 'ThemeController@matchTopic',
            '/relationTable'=> 'ThemeController@relationTableToCreatetheme',
            '/form-event' => 'ThemeController@formEvent',
            '/form/{form}' => 'ThemeController@form',
            '/themes/{id}/topics' => 'TopicController@getTopicforTheme',
        ],
        'POST' => [
            '/topic' => 'TopicController@create',
            '/theme' => 'ThemeController@create',
            '/event' => 'ThemeController@createEvent',
        ],
        'PUT' => [
            '/topics/{id}' => 'TopicController@update',
        ],
        'DELETE' => [
            '/topics/{id}' => 'TopicController@delete',
        ],
    ];
}
