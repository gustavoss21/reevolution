<?php

namespace Services;

use DateTime;
use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\GenerateColumn;


class ConsultService
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
                $this->col('updated_at'),
                $this->colf(['updated_at_diff', 'partial_score'], self::MORE, 'score')
            )->orderBy('score')
            ->limit(3)
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
            $theme['statusLabels'] = StageModel::$STATUS_OPTIONS_LABELS;
        }

        $theme['topic'] = $topics;

        return $theme;
    }

    function getAverageStatus()
    {
        $stage = (new StageModel)
            ->columns(
                $this->colf(
                    $this->colf(['partial_score', 'updated_at_diff'], self::MORE),
                    self::AVERANGE,
                    'point_average'
                ),
                $this->colf('*', self::COUNT, 'amount_event')
            )->orderBy('updated_at')
            ->find();

        return $stage[0];
    }

    function GetEventRecommendation()
    {
        $stage = new StageModel();
        $stage->set('status', StageModel::$STATUS_OPTIONS_NOT_STARTED);
        // obter recomencao de eventos nao estudados
        $event_recommendation = $stage->columns(
            self::col('id'),
            self::col('name'),
            self::col('priority'),
            self::col('domain_level'),
            self::col('status'),
            self::col('topic_id'),
            self::colf(['updated_at_diff', 'partial_score'], self::MORE, 'score')
        )->where('status')
            ->orderBy('score')
            ->limit(5)
            ->find();

        return $event_recommendation;
    }

    function getTimeWithoutStudy()
    {   //objetivo: obter o tempo sem estudar
        $stage = new StageModel();
        $event_without = $stage->columns(
            self::colf('updated_at', 'MAX', 'without_study'),
            self::col('status'),
        )->find();

        $last_study_date = $event_without[0]['without_study'];
        $time_without_study = '';
        // calcula diferença entre agora e a data retornada (em dias ou horas)
        if (empty($last_study_date)) {
            return 'dados indisponiveis';
        }

        $timestamp = strtotime($last_study_date);
        if ($timestamp === false) {
            return 'dados indisponiveis';
        }

        $now = time();
        $diffSeconds = $now - $timestamp;

        $days = floor($diffSeconds / 86400);

        if ($days >= 1) {
            $time_without_study = $days . ' dias';
        } else {
            $hours = floor($diffSeconds / 3600);
            $time_without_study = $hours . ' horas';
        }

        return $time_without_study;
    }

    function getEventsWeekly()
    {
        //obtem a data do inicio da semana
        $start_week = date('Y-m-d', strtotime('monday this week'));
        $stage = new StageModel(['updated_at' => $start_week]);
        $weekly_study = $stage->where('updated_at', '>=', $start_week)
            ->columns(
                $this->colf('*', self::COUNT, 'amount_event_weekly')
            )
            ->find();

        return $weekly_study[0]['amount_event_weekly'];
    }

    function getAverangeTimeWithoutStudy()
    {
        $stage = new StageModel();
        $averange_time = '';
        $average_time = $stage->columns(
            $this->col('updated_at'),
        )
            ->limit(5)
            ->orderBy('updated_at')
            ->find();

        $data =  new DateTime($average_time[0]['updated_at']);
        array_shift($average_time);

        $h = 0;
        $d = 0;
        
        foreach($average_time as $item){
            // calcular a diferença entre as datas
            $d += $data->diff((new DateTime($item['updated_at'])))->format('%a');
            $h += $data->diff((new DateTime($item['updated_at'])))->format('%H');
            $data = new DateTime($item['updated_at']);
        };

        return floor($d / count($average_time)) . ' dias ' . floor($h / count($average_time)) .  ' horas';
    }

    function getMoreTimeWithoutStudy()
    {
        $stage = new StageModel();
        $more_time_data = $stage->limit(3)
            ->orderBy('updated_at')
            ->find();

        return $more_time_data;
    }

    function getMorePriorityEvents()
    {
        $stage = new StageModel();
        $more_priority_data = $stage->limit(3)
            ->orderBy('priority')
            ->find();

        return $more_priority_data;
    }

    function getMoreTimeWithoutStudyEvent()
    {
        $stage = new StageModel();
        $more_time_data = $stage->limit(3)
            ->orderBy('updated_at')
            ->find();

        return $more_time_data;
    }

    function getTotalQuantityEachStatus()
    {
        $stage = new StageModel();
        $total_status = $stage->columns(
            $this->col('status'),
            $this->colf('status', self::COUNT, 'amount_event')
        )
        ->groupBy('status')
        ->find();

        foreach($total_status as &$status){
            $status['label'] = StageModel::$STATUS_OPTIONS_LABELS[$status['status']] ?? 'Desconhecido';
        }

        return $total_status;
    }

    function getRevision(){
        
    }

    function getExtraordinaryEvents(){
        $stage_recomendation = new StageModel([
            'status' => StageModel::$STATUS_OPTIONS_NOT_STARTED,
            'priority'=>StageModel::$label_priorities['critical']
        ]);
        $recomendation_event = $stage_recomendation->where('status')
            ->where('priority')
            ->limit(3)
            ->find();
        
        return $recomendation_event;
    }

}
