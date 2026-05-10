<?php

namespace Models;


use Models\ModelMixin;
// 
class RelationshipTopicAndTag extends  ModelMixin
{
    public $table   = 'relationship_tags_topics';
    public $columns = ['id', 'tag_id', 'topic_id'];
    protected $id, $tag_id, $topic_id, $created_at, $updated_at;
   
}
