<?php

namespace Services;

use Models\ModelMixin;

class Service
{
    public function __construct(public ?ModelMixin $table = null) {}
    function paginate($offset, $limit) {}
}
