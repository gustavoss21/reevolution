<?php

namespace Services;

use DateTime;
use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\TagModel;
use Models\GenerateColumn;


class ChangeData
{
    use GenerateColumn;

    //(prioridade * 1.5) + (ultimaVez dia/ 5) + ((3 - dominio) * 2) + ((2 + status)/2)*3 
    public $stage = '';
    private $tables = [
        'theme' => ThemeModel::class,
        'stage' => StageModel::class,
        'topic' => TopicModel::class,
        'tag' => TagModel::class
    ];

      /**
     * Format data keys by removing table prefixes
     */
    public function formatValueKey($data){
        $tables = [
            'theme',
            'stage',
            'topic',
            'tag'
        ];

        $new_data = []; 
        $pather = "/(^[a-z A-Z]+?)_/";
        foreach($data as $key => $v){
            $has_table = array_find($tables,function($table)use($key){
                return str_contains($key,$table);
            });
            
            if(!$has_table){
                $new_data[$key] = $v;
                continue;
            } ;

            $data = preg_replace('/^[a-z A-Z]+?_/', '', $key);
            $new_data[$data] = $v;
        } 

        return $new_data;
    }
    
    public function createTheme($data){
        // Validate and sanitize input data
        if (empty($data['name']) || empty($data['description'])) {
            throw new \Exception("Name and description are required.");
        }

        $themaModel = new ThemeModel($data);
        $themaModel->set('slug', $themaModel->slug($data['name']));

        // Save thema to database
        $themaModel->insert();

        $themaModel = new ThemeModel(['slug'=>$themaModel->get('slug')]);

        return $themaModel->columns($this->col('id'),$this->col('name'))->where('slug')->find();
    }

    public function createEvent($data)
    {
        $table = null;
        $status = [];
        
        foreach($data as $data_table){

            $table_name = $data_table['name'];
            $table = $this->tables[$table_name];

            if(!$table)return;

            $instanceClass = new $table();            

            $columns = $data_table['child']['main'];

            foreach($columns as $column_data){
                $value  = $column_data['value'];
                $column = $column_data['id'];
                
                if(empty($value)) continue;

                if ($column_data['type'] === 'radio') {
                    $constColumn = strtoupper($column);
                    $value = constant("$table::$constColumn")[$value];
                }

                is_numeric($value) ? $value = (int)$value : $value;

                $instanceClass->set($column, $value);
            }

            $instanceClass->setDataDefault();
            $instanceClass->columns()->insert();
            $status[] = $instanceClass->log;
        }
        
        return $status;

    }
   
}