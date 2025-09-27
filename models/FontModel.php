<?php

namespace Models;


use Models\ModelMixin;
// 
class FontModel extends  ModelMixin
{
    protected $table = 'fonts';
    protected $id, $font, $stage_id, $description, $created_at, $updated_at;

    protected $columns = ['id', 'font', 'description', 'stage_id', 'created_at', 'updated_at'];

    protected $columnsRequiredForMethods = [
        'create'=>['font', 'stage_id'],
        'update'=>['id'],
        'delete'=>['id']
    ];

}
