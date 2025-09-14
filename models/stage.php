<?php

namespace Models;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/models/modelmixin.php';

use ModelMixin;
// 
class Stage extends  ModelMixin
{
    protected $table = 'stages';

    protected $columns = ['id', 'name', 'topic_id', 'slug', 'description', 'created_at', 'updated_at'];

    protected $id, $name, $topic_id, $slug, $description, $created_at, $updated_at;

    function showAtribuits()
    {
        print_r(get_object_vars($this));
    }
}
