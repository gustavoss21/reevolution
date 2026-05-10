<?php

namespace Models;


use Models\ModelMixin;
// 
class FontModel extends  ModelMixin
{
    public $table = 'fonts';
    protected $id, $name, $stage_id, $description, $created_at, $updated_at;

    protected $columns = ['id', 'name', 'description', 'stage_id', 'created_at', 'updated_at'];

    protected $columnsRequiredForMethods = [
        'create'=>['name', 'stage_id'],
        'update'=>['id'],
        'delete'=>['id']
    ];

}
