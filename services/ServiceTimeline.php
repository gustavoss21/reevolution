<?php

namespace Services;

use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\GenerateColumn;


class ServiceTimeline
{
    use GenerateColumn;

    //(prioridade * 1.5) + (ultimaVez dia/ 5) + ((3 - dominio) * 2) + ((2 + status)/2)*3 
    public $stage = '';
    function timeline()
    {
        $stages = (new StageModel())
            ->columns(
                $this->col('id'),
                $this->col('name'),
                $this->col('priority'),
                $this->col('domain_level'),
                $this->col('status'),
                $this->col('topic_id'),
                $this->colf(['updated_at_diff', 'partial_score'], self::MORE, 'score')
            )->orderBy('score')
            ->limit(1)
            ->find();

        $themes = [];

        foreach ($stages as $stage) {
            $topic = StageModel::connect_one_to_many_topics($stage['topic_id']);
            $topic[0]['stage'] = $stage;

            $theme = TopicModel::connect_one_to_many_theme($topic[0]['theme_id']);
            $theme[0]['topic'] = $topic[0];
            $themes[] = $theme[0];
        }

        return $themes;
    }

    function getThemefullData(array $theme)
    {


        $topic = new TopicModel(['theme_id' => $theme['id']]);
        $topics = $topic->where('theme_id')->find();

        foreach ($topics as &$topic) {
            $stage = new StageModel(['topic_id' => $topic['id']]);

            $topic['stage'] = $stage->where('topic_id')->find();
            $theme['statusLabels'] = StageModel::$STATUS_OPTIONS;
        }

        $theme['topic'] = $topics;

        return $theme;
    }

    function getAverageStatus()
    {
        $stage = (new StageModel)
            ->columns(
                $this->colf(
                    ['partial_score', 'updated_at_diff'],
                    self::AVERANGE,
                    'point_average'
                ),
                $this->colf('*',self::COUNT, 'amount_event')
            )->orderBy('updated_at')
            ->find();

        return [
            'averange' => $stage,
            'options' => StageModel::$STATUS_OPTIONS
        ];
    }
}
