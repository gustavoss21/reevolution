<?php

namespace Services;

use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;

class ServiceTimeline
{
    //(prioridade * 1.5) + (ultimaVez dia/ 5) + ((3 - dominio) * 2) + ((2 + status)/2)*3 
    public $stage = '';
    static function timeline(){
        $stages = StageModel::getTimeline();
        $themes = [];

        foreach($stages as $stage){
            $topic = StageModel::connect_one_to_many_topics($stage['topic_id']);
            $topic[0]['stage'] = $stage; 

            $theme = TopicModel::connect_one_to_many_theme($topic[0]['theme_id']);
            $theme[0]['topic'] = $topic[0];
            $themes[] = $theme[0];
        }

        return $themes;
    }

    static function getThemefullData(array $theme)
    {


        $topic = new TopicModel(['theme_id'=>$theme['id']]);
        $topics = $topic->find(['topic_id']);

        foreach($topics as &$topic){
            $stage = new StageModel(['topic_id'=>$topic['id']]);

            $topic['stage'] = $stage->find(['topic_id']);
            $theme['statusLabels'] = StageModel::$STATUS_OPTIONS;
        }

        $theme['topic'] = $topics;

        return $theme;
    }

    static function getAverageStatus(){
        return [
            'averange'=>StageModel::getPointAverage(),
            'options'=>StageModel::$STATUS_OPTIONS
        ];
    }
}