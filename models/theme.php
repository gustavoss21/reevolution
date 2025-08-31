<?php
namespace Models;

use ModelMixin;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . 'modelmixin.php';
// 
class Theme extends  ModelMixin{
    private $id, $name, $slug, $description, $created_at, $updated_at;
    
    
   
}