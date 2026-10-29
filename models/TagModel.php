<?php

namespace Models;


use Models\ModelMixin;
// 
class TagModel extends  ModelMixin
{
    public string $table = 'tags';
    public array $columns = ['id', 'tag','description'];
    protected string $id, $description;
    protected string|array $tag;

    public static array $LABELS = [
        'tag'=>'O evento é sobre o que',
        'description'=>'Descrição'
    ];

    protected array $columnsRequiredForMethods = [
        'create'=>['name'],
        'update'=>['id'],
        'delete'=>['id']
    ];
}
