<?php

namespace Services;
use Services\Service;
use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\TagModel;
use Models\GenerateColumn;


class AcompanimentService extends Service
{
    use GenerateColumn;

    public $stage = '';
    private $tables = [
        'themes' => ThemeModel::class,
        'stages' => StageModel::class,
        'topics' => TopicModel::class,
        'tags' => TagModel::class
    ];

    public $tableNamesTransleted = [
        'temas' => 'themes',
        'estagios' => 'stages',
        'topicos' => 'topics',
        'tags' => 'tags'
    ];

    function spellLabel($dataPossible){
        [$labelName,$valueSearch] = explode(':', $dataPossible);

        if(!array_key_exists($labelName, $this->tableNamesTransleted)){
            return null;
        }

        $tableName  = $this->tableNamesTransleted[$labelName];
        $modelClass = $this->tables[$tableName];
        
        return [
            'model' => $modelClass,
            'valueSearch' => $valueSearch
        ];
        
    }

    function filterforTable($dataPossible){
        $resultSpell = $this->spellLabel($dataPossible);

        if(!$resultSpell){
            return null;
        }

        $modelClass = $resultSpell['model'];
        $valueSearch = $resultSpell['valueSearch'];
        $instanceModel = new $modelClass();
        $instanceModel->set('name', $valueSearch);
        $instanceModel->where('name');
        return $instanceModel;
    }
}