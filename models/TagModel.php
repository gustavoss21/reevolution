<?php

namespace Models;


use Models\ModelMixin;
// 
class TagModel extends  ModelMixin
{
    protected $table = 'tags';
    public $columns = ['id', 'tag','description'];
    protected $id, $tag, $description;

    static $LABELS = [
        'tag'=>'O evento é sobre o que',
        'description'=>'Descrição'
    ];

    protected $columnsRequiredForMethods = [
        'create'=>['tag'],
        'update'=>['id'],
        'delete'=>['id']
    ];
}
