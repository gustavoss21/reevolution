<?php

namespace Models;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/models/modelmixin.php';

use Models\ModelMixin;
// 
class StageModel extends  ModelMixin
{
    protected $table = 'stages';

    protected $columns = [
        'id', 'name', 'topic_id', 'slug', 'description',
        'created_at', 'updated_at','summary','synthesis',
        'status','domain_level','attention','learning_stage'
    ];

    public static $STATUS_OPTIONS = [
        'NÂO INICIADO',
        'EM ANDAMENTO',
        'FINALIZADO'
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
        'create'=>['name', 'topic_id', 'status', 'domain_level', 'learning_stage'],
        'update'=>['id'],
        'delete'=>['id']
    ];

    protected $id, $name, $topic_id, $slug, $description, $created_at, $updated_at, $summary, $synthesis, $status, $domain_level, $attention, $learning_stage;
}
