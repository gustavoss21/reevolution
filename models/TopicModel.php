<?php

namespace Models;


use Models\ModelMixin;
// 
class TopicModel extends  ModelMixin
{
    protected $table = 'topics';

    protected $columns = ['id', 'name', 'slug', 'description', 'theme_id', 'created_at', 'updated_at'];

    protected $columnsRequiredForMethods = [
        'create'=>['name', 'theme_id'],
        'update'=>['id'],
        'delete'=>['id']
    ];

    protected $id, $name, $theme_id, $slug, $description, $created_at, $updated_at;
}
