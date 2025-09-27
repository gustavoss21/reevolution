<?php

namespace Models;

use Models\ModelMixin;
 
class StageModel extends  ModelMixin
{
    protected $id, $name, $topic_id, $slug, $description, $created_at, $updated_at, $summary, $synthesis, $status, $domain_level, $attention, $learning_stage, $priority;

    protected $table = 'stages';

    protected $columns = [
        'id', 'name', 'topic_id', 'slug', 'description',
        'created_at', 'updated_at','summary','synthesis',
        'status','domain_level','attention','learning_stage',
        'priority'
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
        1=>'APRENDER A FAZER',
        'EXPERIÊNCIA CONCRETA',
        'PENSAR E CRIAR ABSTRAÇÃO',
        'FAZER EXERCICIOS'
    ];

    protected $columnsRequiredForMethods = [
        'create'=>['name', 'topic_id', 'status', 'domain_level', 'learning_stage', 'priority'],
        'update'=>['id'],
        'delete'=>['id']
    ];

    static function connect_one_to_many_topics($topic_id){
        $topic = new TopicModel();
        $topic->set('id', $topic_id);
        $topic = (new StageModel)->relationship($topic);
        return $topic;
    }

}

