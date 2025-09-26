<?php

namespace Models;

use Models\ModelMixin;
// 
class ThemeModel extends ModelMixin
{
    protected $table = 'themes';

    protected $columns = ['id', 'name', 'slug', 'description', 'created_at', 'updated_at'];

    protected $columnsRequiredForMethods = [
        'create'=>['name','slug'],
        'update'=>['id'],
        'delete'=>['id']
    ];
    protected $id, $name, $slug, $description, $created_at, $updated_at;

}
