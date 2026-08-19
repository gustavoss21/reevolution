<?php

namespace Models;

use Models\ModelMixin;
// 
class ThemeModel extends ModelMixin
{
    public string $table = 'themes';
    protected string $name, $slug, $description, $created_at, $updated_at;
    protected int|array $id;
    public array $columns = ['id', 'name', 'slug', 'description', 'created_at', 'updated_at'];
    static array $LABELS = [
        'name'=>'Nome',
        'description'=> 'Descrição'
    ];

    protected array $columnsRequiredForMethods = [
        'create'=>['name','slug'],
        'update'=>['id'],
        'delete'=>['id']
    ];

    public function setDataDefault()
    {
        $this->set('slug', $this->slug($this->get('name')));
    }
    
}