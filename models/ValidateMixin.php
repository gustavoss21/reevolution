<?php

namespace Models;

trait ValidateMixin
{

    protected $columnsRequiredForMethods = [
        'update' => [],
        'create' => [],
        'delete' => []
    ];

    public function validateRequiredFields($requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (empty($this->{$field})) {
                throw new \Exception("The field {$field} is required.");
            }
        }
    }

}