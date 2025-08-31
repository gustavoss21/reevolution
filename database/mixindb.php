<?php

require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Class to build SQL queries dynamically.
 */
class MixinQuerybuild{

    protected $table, $columns, $where, $data;

    public function __construct(string $table, array $where, array $data = [],) {
        $this->where = $where;
        $this->data = $data;
        $this->columns = array_keys($data);
        $this->table = $table;
    }

    /**
     * Format parameters for SQL queries. 
     *
     * @param array $queryPartition The parameters to format.
     * @param string $separator The separator to use between parameters.
     * @return string The formatted string for SQL queries.
     */
    function formatParamts($queryPartition,$separator= ' '){
        
        if (empty($queryPartition)) return null;

        $lastIndex = count($queryPartition) - 1;
        $result = '';

        foreach ($queryPartition as $i => $item) {
            $result .= $item;
            if ($i !== $lastIndex) {
                $result .= $separator;
            }
        }
        return $result;
    }

    /**
     * Format columns for SQL queries with placeholders.
     *
     * @param array $columns The columns to format.
     * @return string The formatted string for SQL queries.
     */
    function formatParamtsForValue(array $columns,$hascolumns = null){
        if(empty($columns)) return '';

        $parameterFormated = '';
        $lastIndex = count($columns) - 1;

        foreach($columns as $key => $value){
            $column = $hascolumns ? $value. '= :' : ':';
            $parameterFormated.= $column. $value;
            if(!($lastIndex === $key)){
                $parameterFormated .= ', ';
            }
    
        }
        return $parameterFormated;
    }
 
    function formatParamtsForWhere(array $data)
    {
        if (empty($data) || isset($columns['clasure'])) return '';

        // Object to manage the state of the WHERE clause construction
        $whereobject = new class {
            public $whereobServationIndex = null;
            public $columns = null;
            public $whereVerification = [];
            public $where = '';
        };
        
        $whereobject->columns = array_keys($data['clasure']);
        $count = 0;

        foreach ($whereobject->columns as $whereItem) {
            if($count > 0 and $whereobject->whereVerification[$count -1]['requireNext']){
                $whereobject->whereVerification[$count -1]['status'] = true;
                $whereobject->where .=  $whereobject->whereVerification[$count -1]['arg'];
            }

            $whereobject->whereVerification[$count] = ['arg' => $whereItem . '= :' . $whereItem . ' ', 'status' => true, 'requireNext' => false];;
            $whereobject->where .=  $whereobject->whereVerification[$count]['arg'];
            
            if (isset($data['operator'])){
                $whereobject->whereVerification[$count] = ['arg' => ' ' . $data['operator'] . ' ', 'status' => false, 'requireNext' => true];
            }

            $count++;

        }
    
        return 'WHERE ' . $whereobject->where;
    }
    
}