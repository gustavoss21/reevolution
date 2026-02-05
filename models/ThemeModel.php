<?php

namespace Models;

use Models\ModelMixin;
// 
class ThemeModel extends ModelMixin
{
    protected $table = 'themes';
    protected $id, $name, $slug, $description, $created_at, $updated_at;
    protected $columns = ['id', 'name', 'slug', 'description', 'created_at', 'updated_at'];
    static $LABELS = [
        'name'=>'Nome',
        'description'=> 'Descrição'
    ];

    protected $columnsRequiredForMethods = [
        'create'=>['name','slug'],
        'update'=>['id'],
        'delete'=>['id']
    ];

    public function setDataDefault()
    {
        $this->set('slug', $this->slug($this->get('name')));
    }
    
}
