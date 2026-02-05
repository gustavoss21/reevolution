<?php

namespace Models;

use Models\ModelMixin;
use Models\ColumnTrait;
use DateTime;

class StageModel extends  ModelMixin implements FuncColumnInterface
{
    use ColumnTrait;

    public $id, $name, $topic_id, $slug, $description, $created_at, $updated_at, $summary, $synthesis, $status, $domain_level, $attention, $learning_stage, $priority, $partial_score, $more_advanced;
    protected $table = 'stages';
    public $columns = [
        'id',
        'name',
        'more_advanced',
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

    static $PRIORITE_LABEL = [
        1 => 'Baixa',
        2 => 'Média',
        3 => 'Alta',
        4 => 'Critica'
    ];

    public static $STATUS_OPTIONS_LABELS = [
        -1 => 'NÃO INICIADO',
        0 => 'EM ANDAMENTO',
        1 => 'FINALIZADO'
    ];

    public static $LABELS = [
        'more_advanced'         => 'Deveria estar mais avançado neste evento',
        'more_advanced_op_yes'  => 'Sim',
        'more_advanced_op_no'   => 'Não',
        'topic_id'              => 'Esta matéria tem como pré-requisito outros eventos, quais',
        'description'           => 'Topa digitar por voz, e explicar o que entende sobre o assunto',
        'learning_stage'        => 'Em qual estagio voce está, esta há',
        'learning_stage_op_a'   => 'Aprender a fazer',
        'learning_stage_op_e'   => 'Ter experiência Concreta',
        'learning_stage_op_p'   => 'Pensar e Criar Abstração',
        'learning_stage_op_f'   => 'Fazer Exercícios e Aplicar',
        'learning_stage_op_r'   => 'Revisar',
        'domain_level'          => 'Nível de Domínio',
        'domain_level_op_ini'   => 'Iniciante',
        'domain_level_op_inter' => 'Intermediário',
        'domain_level_op_av'    => 'Avançado',
        'priority'              => 'Prioridade',
        'priority_op_1'         => 'Baixa',
        'priority_op_2'         => 'Média',
        'priority_op_3'         => 'Alta',
        'priority_op_4'         => 'Crítica'

    ];

    const DOMAIN_LEVEL = [
        'domain_level_op_ini'=>1,
        'domain_level_op_inter'=>2,
        'domain_level_op_av'=>3
    ];

    const MORE_ADVANCED = [
        'more_advanced_op_yes' => 1,
        'more_advanced_op_no' => 0
    ];

    const LEARNING_STAGE = [
        'learning_op_stage_a' => 1,
        'learning_op_stage_e' => 2,
        'learning_op_stage_p' => 3,
        'learning_op_stage_f' => 4
    ];

    static $label_key_priorities = [
        1,
        2,
        3,
        4,
    ];

    protected $columnsRequiredForMethods = [
        'create' => ['name', 'slug', 'topic_id', 'status', 'domain_level', 'learning_stage', 'priority', 'partial_score', 'more_advanced'],
        'update' => ['id'],
        'delete' => ['id']
    ];

    static $STATUS_OPTIONS_FINALIZED = 1;
    static $STATUS_OPTIONS_NOT_STARTED = -1;
    static $STATUS_OPTIONS_IN_PROGRESS = 0;

    static function connect_one_to_many_topics($topic_id)
    {
        $topic = new TopicModel();
        $topic->set('id', $topic_id);
        $topic_item = (new StageModel)->relationship($topic);
        return $topic_item;
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

    public function setDataDefault(){
        $this->get('status') ?? $this->set('status', self::$STATUS_OPTIONS_NOT_STARTED);
        $this->get('name') ?? $this->set('name', 'init_stage_' . uniqid());
        $this->set('slug', $this->slug($this->get('name')));
    }
}
