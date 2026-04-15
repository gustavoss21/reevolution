<?php

namespace Services;

use Models\ModelMixin;

abstract class Service
{
    public $table;
    public function __construct(ModelMixin|string $table = '')
    {
        $this->table = $table? new $table: '';
    }

    function paginate($offset, $limit)
    {
        $this->table->limit($limit, $offset);
        return $this;
    }
}
