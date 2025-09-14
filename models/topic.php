<?php

namespace Models;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/models/modelmixin.php';

use ModelMixin;
// 
class Topic extends  ModelMixin
{
    protected $table = 'topics';

    protected $columns = ['id', 'name', 'slug', 'description', 'theme_id', 'created_at', 'updated_at'];

    // protected $id, $name, $slug, $description, $created_at, $updated_at;
    protected $id, $name, $theme_id, $slug, $description, $created_at, $updated_at;

    function showAtribuits()
    {
        print_r(get_object_vars($this));
    }
}
