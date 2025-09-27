<?php

namespace Models;


use Models\ModelMixin;
// 
class TopicModel extends  ModelMixin
{
    protected $table = 'topics';
    public $columns = ['id', 'name', 'slug', 'description', 'theme_id', 'created_at', 'updated_at'];
    protected $id, $name, $theme_id, $slug, $description, $created_at, $updated_at;

    protected $columnsRequiredForMethods = [
        'create'=>['name','slug', 'theme_id'],
        'update'=>['id'],
        'delete'=>['id']
    ];

    static function connect_one_to_many_theme($theme_id)
    {
        $Instheme = new ThemeModel();
        $Instheme->set('id', $theme_id);

        $theme = (new TopicModel)->relationship($Instheme);
        
        return $theme;
    }
}
