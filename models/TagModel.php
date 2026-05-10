<?php

namespace Models;


use Models\ModelMixin;
// 
class TagModel extends  ModelMixin
{
    public $table = 'tags';
    public $columns = ['id', 'name','description'];
    protected $id, $name, $description;

    static $LABELS = [
        'name'=>'O evento é sobre o que',
        'description'=>'Descrição'
    ];

    protected $columnsRequiredForMethods = [
        'create'=>['name'],
        'update'=>['id'],
        'delete'=>['id']
    ];
}
