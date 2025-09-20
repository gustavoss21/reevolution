<?php

namespace Models;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/models/modelmixin.php';

use Models\ModelMixin;
// 
class StageModel extends  ModelMixin
{
    protected $table = 'stages';

    protected $columns = ['id', 'name', 'topic_id', 'slug', 'description', 'created_at', 'updated_at'];

    protected $columnsRequiredForMethods = [
        'create'=>['name', 'topic_id'],
        'update'=>['id'],
        'delete'=>['id']
    ];

    protected $id, $name, $topic_id, $slug, $description, $created_at, $updated_at;

    function showAtribuits()
    {
        print_r(get_object_vars($this));
    }
}
