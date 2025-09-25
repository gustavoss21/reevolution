<?php

namespace Services;

use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;

class ServiceTimeline
{
    //(prioridade * 1.5) + (ultimaVez dia/ 5) + ((3 - dominio) * 2) + ((2 + status)/2)*3 
    public $stage = '';
    static function teste(){
        $stage = StageModel::getTimeline();
        return $stage;
    }
}