<?php

namespace Models;
require dirname(__FILE__,2) . '/vendor/autoload.php';

use DateTime;
use Models\ModelMixin;
// 
class RelationshipTopicAndTag extends ModelMixin
{
    public string $table   = 'relationship_tags_topics';
    public array $columns = ['id', 'tag_id', 'topic_id'];
    public array|int $id, $tag_id, $topic_id;
    public string|DateTime $created_at, $updated_at;
   
}