<?php

namespace Models;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/models/modelmixin.php';

use ModelMixin;
// 
class Theme extends  ModelMixin
{
    protected $table = 'themes';

    protected $columns = ['id', 'name', 'slug', 'description', 'created_at', 'updated_at'];

    protected $id, $name, $slug, $description, $created_at, $updated_at;

    function showAtribuits()
    {
        print_r(get_object_vars($this));
    }
}

$tema = new Theme([
    // 'id' => 1,
    'name' => 'Tema teste',
    'slug' => 'tema-real',
    'description' => 'Descrição do tema teste',
    'created_at' => date('Y-m-d H:i:s'),
]);
