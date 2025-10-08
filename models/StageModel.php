<?php

namespace Models;

use Models\ModelMixin;
use DateTime;

class StageModel extends  ModelMixin
{
    protected $id, $name, $topic_id, $slug, $description, $created_at, $updated_at, $summary, $synthesis, $status, $domain_level, $attention, $learning_stage, $priority, $partial_score;

    protected $table = 'stages';

    public $columns = [
        'id',
        'name',
        'topic_id',
        'slug',
        'description',
        'created_at',
        'updated_at',
        'summary',
        'synthesis',
        'status',
        'domain_level',
        'attention',
        'learning_stage',
        'priority',
        'partial_score'
    ];

    static $priorities = [
        1 => 'Low',
        2 => 'Medium',
        3 => 'High',
        4 => 'Critical'
    ];

    public static $STATUS_OPTIONS = [
        'FINALIZADO',
        'NÂO INICIADO',
        'EM ANDAMENTO'
    ];

    public static $DOMAIN_LEVEL_OPTIONS = [
        'INICIANTE',
        'INTERMEDIÁRIO',
        'AVANÇADO'
    ];

    public static $LEARNING_STAGE_OPTIONS = [
        1 => 'APRENDER A FAZER',
        'EXPERIÊNCIA CONCRETA',
        'PENSAR E CRIAR ABSTRAÇÃO',
        'FAZER EXERCICIOS'
    ];

    protected $columnsRequiredForMethods = [
        'create' => ['name', 'topic_id', 'status', 'domain_level', 'learning_stage', 'priority', 'partial_score'],
        'update' => ['id'],
        'delete' => ['id']
    ];

    static function connect_one_to_many_topics($topic_id)
    {
        $topic = new TopicModel();
        $topic->set('id', $topic_id);
        $topic = (new StageModel)->relationship($topic);
        return $topic;
    }

    static function getPointAverage()
    {
        $columns_meta = [
            'columns' => [
                'averange' => [
                    'partial_score',
                    'updated_at_diff',
                    'as' => 'point_average'
                ],
                'count' => ['*', 'as' => 'amount_event']

            ],
            'order' => 'updated_at',
        ];
        return (new StageModel)->find(null,);
    }

    public function insert()
    {

        $this->setScore();
        parent::insert();
    }

    private function setScore()
    {
        $pointP = ($this->priority * 1.5);
        // $PointDate = ((new DateTime($this->updated_at))->diff((new DateTime()))->format('%a')) / 5.0;
        $pointD = (3 - $this->domain_level) * 2;
        $pointS = 2 + $this->status;
        $partial_score = $pointP + $pointD + $pointS;

        $this->set('partial_score', $partial_score);
    }
}
