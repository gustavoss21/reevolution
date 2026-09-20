<?php

namespace Models;


use Models\ModelMixin;
// 
class FontModel extends  ModelMixin
{
    public string $table = 'fonts';
    protected $id, $name, $stage_id, $description, $created_at, $updated_at;

    public array $columns = ['id', 'name', 'description', 'stage_id', 'created_at', 'updated_at'];

    protected array $columnsRequiredForMethods = [
        'create'=>['name', 'stage_id'],
        'update'=>['id'],
        'delete'=>['id']
    ];

}
