<?php

namespace Models;

trait ValidateMixin
{
    public $errors = [];
    public $log = [];

    protected $columnsRequiredForMethods = [
        'update' => [],
        'create' => [],
        'delete' => []
    ];

    public function validateRequiredFields($requiredFields)
    {
        $errorFilds = [];

        foreach ($requiredFields as $field) {
            if (empty($this->{$field}) && $this->{$field} !== 0) {
                $errorFilds[$field] = "The field {$field} is required.";
                $this->logErrorValidation($field);
            }
        }
    }

    private function log($status, $message = '', $input = 'db')
    {

        $this->log[$input] = [
            'table' => $this->table,
            'status' => $status,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    public function logErrorDb($message = '')
    {
        $this->errors[] = $this->log(500, $message);
        return $this->log;
    }

    public function logErrorValidation($input)
    {
        $this->errors[] = $this->log(500, "The field {$input} is required.", $input);
        return $this->log;
    }

}