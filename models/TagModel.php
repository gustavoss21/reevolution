<?php

namespace Models;


use Models\ModelMixin;
// 
class TagModel extends  ModelMixin
{
    public string $table = 'tags';
    public array $columns = ['id', 'name','description'];
    protected string $id, $name, $description;

    public static array $LABELS = [
        'name'=>'O evento é sobre o que',
        'description'=>'Descrição'
    ];

    protected array $columnsRequiredForMethods = [
        'create'=>['name'],
        'update'=>['id'],
        'delete'=>['id']
    ];
}
