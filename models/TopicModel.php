<?php

namespace Models;


use Models\ModelMixin;
// 
class TopicModel extends  ModelMixin
{
    public $table = 'topics';
    public $columns = ['id', 'name', 'slug', 'description', 'theme_id', 'created_at', 'updated_at', 'can_explain', 'times_week_necessary', 'domain_week', 'started_study', 'lot_to_discuss', 'actived', 'end_date', 'study_time'];
    protected $id, $name, $theme_id, $topic_id, $slug, $description, $created_at, $updated_at, $can_explain, $times_week_necessary, $domain_week, $started_study, $lot_to_discuss, $actived, $end_date, $study_time;
    static $LABELS = [
        'name'              => 'Tópico do Evento',
        'theme_id'          => 'Tema do Evento',
        'domain_week'       => 'quanto tempo é necessário para dominar o assunto',
        // 'domain_week'       => 'quantas vezes por semana é necessário para ter dominio sobre',
        'domain_week_op_f'  => 'já sei o suficiente',
        'domain_week_op_1s' => '1+ semana',
        'domain_week_op_1m' => '1+ meses',
        'domain_week_op_6m' => '6+ meses',
        'domain_week_op_1y' => '1 Ano',
        'tag_id'            => 'deseja adicionar tags ao evento',
        'end_date'          => 'expectativa de Término',
        // 'actived'=>'esta ativo',
        'times_week_necessary'  => 'Quantas vezes por semana é necessário estudar',
        'lot_to_discuss'        => 'É preciso consultar muito material para o evento',
        'lot_to_discuss_op_no'  => 'Não',
        'lot_to_discuss_op_yes' => 'Sim',
        'description'           => 'Descrição',
        'started_study'         => 'Você já começou a estudar esse assunto',
        'started_study_op_no'   => 'Não',
        'started_study_op_yes'  => 'Sim',
        'started_study'         => 'Você já começou a estudar esse assunto',
        'started_study_op_no'   => 'Não',
        'started_study_op_yes'  => 'Sim',
        '_condition' => [
            'conditioned' => [
                'study_time'         => 'estuda o assunto a quanto tempo',
                'study_time_op_1m'   => '1+ mês',
                'study_time_op_6m'   => '6+ meses',
                'study_time_op_1y'   => '1+ ano',
                'can_explain'        => 'Se tivesse que explicar esse conteúdo a alguém, hoje, conseguiria?',
                'can_explain_op_no'  => 'Não',
                'can_explain_op_yes' => 'Sim',
            ],
            'label'          => [
                'started_study' => 'Você já começou a estudar esse assunto',
                'started_study_op_no'  => 'Não' ,
                'started_study_op_yes' => [
                    'value' => 'Sim',
                    'action'=>''
                    ],
            ],
            'conditioner'=> 'started_study.started_study_op_yes',
        ],
        '_event' => [[
            'COLUMN_NAME' => 'event_id',
            'DATA_TYPE' => 'int',
            'CHARACTER_MAXIMUM_LENGTH' => null,
            'COLUMN_DEFAULT' => '',
            'IS_NULLABLE' => 'YES',
            'label' => 'Esta matéria tem como pré - requisito outros eventos, quais '
        ]],
    ];

    const DOMAIN_WEEK = [
        'domain_week_op_f' => 0,
        'domain_week_op_1s'=>7,
        'domain_week_op_1m'=>30,
        'domain_week_op_6m'=>180,
        'domain_week_op_1y' =>365
    ];

    const STUDY_TIME = [
        'study_time_op_1m' =>1,
        'study_time_op_6m' =>6,
        'study_time_op_1y' =>12
    ];

    const CAN_EXPLAIN = [
        'can_explain_op_no'=>0,
        'can_explain_op_yes'=>1
    ];

    const STARTED_STUDY = [
        'started_study_op_no'=>0,
        'started_study_op_yes'=>1
    ];

    const LOT_TO_DISCUSS = [
        'lot_to_discuss_op_no'=>0,
        'lot_to_discuss_op_yes'=>1
    ];

    protected $columnsRequiredForMethods = [
        'create' => ['name', 'slug', 'theme_id'],
        'update' => ['id'],
        'delete' => ['id']
    ];

    public function setDataDefault()
    {
        $this->set('slug', $this->slug($this->get('name')));
    }
    
    static function connect_one_to_many_theme($theme_id)
    {
        $Instheme = new ThemeModel();
        $Instheme->set('id', $theme_id);

        $theme = (new TopicModel)->relationship($Instheme);

        return $theme;
    }

    static function connect_tags(int $topic_id, int $tag_id)
    {
        $Instage = new RelationshipTopicAndTag();
        $Instage->set('tag_id', $tag_id);
        $Instage->set('topic_id', $topic_id);
        
        return $Instage->insert();
    }
}
