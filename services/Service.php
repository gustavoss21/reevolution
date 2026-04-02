<?php

namespace Services;

use Models\ModelMixin;

abstract class Service
{
    public function __construct(public ModelMixin $table) {}

    function paginate($offset, $limit)
    {
        // $table->
        return $this;
    }
}
