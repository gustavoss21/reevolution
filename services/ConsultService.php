<?php

namespace Services;

use DateTime;
use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\TagModel;
use Models\GenerateColumn;
use Services\Service;


class ConsultService extends Service
{
    use GenerateColumn;

    //(prioridade * 1.5) + (ultimaVez dia/ 5) + ((3 - dominio) * 2) + ((2 + status)/2)*3 
    public $stage = '';
    private $tables = [
        'themes' => ThemeModel::class,
        'stages' => StageModel::class,
        'topics' => TopicModel::class,
        'tags' => TagModel::class
    ];

    function searchForOther($byTable, $byTableId)
    {
        $columnTableId = $byTable . '_id';
        $this->table->set($columnTableId, $byTableId);
        $this->table   = $this->table->where('theme_id');
        return $this;
    }

    function timeline()
    {
        $stages = $this->table
            ->columns(
                $this->col('id'),
                $this->col('name'),
                $this->col('priority'),
                $this->col('domain_level'),
                $this->col('status'),
                $this->col('topic_id'),
                $this->col('updated_at'),
                $this->colf(['updated_at_diff', 'partial_score'], self::MORE, 'score')
            )->orderBy('score')->find();

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

    function filterTopic($data)
    {
        $topic = new TopicModel();

        foreach ($data as $column => $value) {
            $topic->set($column, $value);
            $topic->where($column);
        }

        $topic->find();
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
        $stage = new StageModel(['status' => StageModel::$STATUS_OPTIONS_NOT_STARTED]);
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
            ->limit(3)
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
        $timestamp = strtotime($last_study_date);
        if ($timestamp === false && empty($last_study_date)) {
            return 'Vazio';
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
        $weekly_study = $stage->where('updated_at', $stage::OPERADORES['GTE'])
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

        foreach ($average_time as $item) {
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

        foreach ($total_status as &$status) {
            $status['label'] = StageModel::$STATUS_OPTIONS_LABELS[$status['status']] ?? 'Desconhecido';
        }

        return $total_status;
    }

    function getRevision() {}

    function getMatchEvent($event, $table = 'themes')
    {
        $table = $this->tables[$table];
        $instaceModel = new $table(['name' => $event['name']]);

        return $instaceModel->columns($this->col('id'), $this->col('name'))->where('name', $instaceModel::OPERADORES['LIKE'])->find();
    }

    function getColData($event)
    {
        $tables = ['tags' => TagModel::class, 'topics' => TopicModel::class];
        $event_key = array_key_first($event);

        if (!(array_key_exists($event_key, $tables) && count($event) === 1)) return ['error'];

        $tabel_instance = new ($tables[$event_key])($event[$event_key]);

        return $tabel_instance->where('id')->find();
    }

    function getForm($table, $without_col = [])
    {
        $model = $this->tables[$table];
        $instance = new ($model)();
        $data['data'] = $instance->getForm($without_col);
        //label
        $data['labelS'] = $model::$LABELS;
        return $data;
    }

    function getFormRecursive($form)
    {
        $table = $form . 's';
        $data = [];
        $data[$form] = $this->getForm($table);
        $columns = $data[$form]['labelS'];
        $pathern = "/.*_id$/";
        $columnsWithId = preg_filter($pathern, '$0', array_keys($columns));

        foreach ($columnsWithId as $column) {
            $tableName = str_replace('_id', 's', $column);
            $data[$column . '_child'] = $this->getForm($tableName);
        }

        return $data;
    }

    function find()
    {
        return $this->table->find();
    }
}
