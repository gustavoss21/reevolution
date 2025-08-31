<?php

class ModelMixin{
    function get($paramether)
    {
        if (property_exists($this, $paramether)) {
            return $this->{$paramether};
        }
    }

    function set($paramether, $value)
    {
        if (!property_exists($this, $paramether)) return;
        if (empty($paramether)) return;

        $this->{$paramether} = $value;
    }

    
}