<?php

namespace Database;

use BadFunctionCallException;
use SQLite3Exception;
use Models\ColumnTrait;

/**
 * Class to build SQL queries dynamically.
 */
class MixinQuerybuild 
{
    use \Models\ColumnTrait;

    protected $columns, $where, $limit;
    private $meta;
    protected $order_by, $group_by = '';
    const OPERADORES = ['EQ' => '=', 'GT' => '>', 'LT' => '<', 'GTE' => '>=', 'LTE' => '<=', 'NEQ' => '<>', 'LIKE' => 'LIKE', 'IN' => 'IN', 'NOT IN' => 'NOT IN'];
    const OPERADORES_LOGICOS = ['AND' => 'AND', 'OR' => 'OR'];
    const OPTION_CONSTRUCT_COLUMN = ['defult'=> 'defult','personal'=> 'ColumnTrait'];



    public function __construct( public string $table, public string $FuncColumnConstruct= self::OPTION_CONSTRUCT_COLUMN['defult']){}

    /**
     * Format parameters for SQL queries. 
     *
     * @param array $queryPartition The parameters to format.
     * @param string $separator The separator to use between parameters.
     * @return string The formatted string for SQL queries.
     */
    function formatParamts($queryPartition, $separator = ' ')
    {

        if (empty($queryPartition)) return null;

        $lastIndex = count($queryPartition) - 1;
        $result = '';

        return implode($separator,$queryPartition);
    }

    /**
     * Format columns for SQL queries with placeholders.
     *
     * @param array $columns The columns to format.
     * @return string The formatted string for SQL queries.
     */
    function formatParamtsForValue(array $columns, $hascolumns = null)
    {
        if (empty($columns)) return '';

        $parameterFormated = '';
        $lastIndex = count($columns) - 1;

        foreach ($columns as $key => $value) {

            $column = $hascolumns ? $value . '= :' : ':';
            $parameterFormated .= $column . $value;
            if (!($lastIndex === $key)) {
                $parameterFormated .= ', ';
            }
        }
        return $parameterFormated;
    }

    function formatParamtsForWhere()
    {
        if( empty($this->where)) return '';

        $whereFormated = 'WHERE ' . $this->where['column'] . ' ' . $this->where['operator'] . ' :' . $this->where['column'];
        $nextWhere = $this->where['next'] ?? null;
        
        while($nextWhere){
            $whereFormated .= " {$nextWhere['operator']} {$nextWhere['clousere']['column']} {$nextWhere['clousere']['operator']} :{$nextWhere['expression']['column']}";
            
            if(!empty($nextWhere['next'])) {
                $nextWhere = $nextWhere['next'];
                continue;
            };
            
            break;

        }        

        return $whereFormated;
    }

    /**
     * Filters data based on specified conditions.
     *
     * @param mixed $data The data to be filtered, typically an array or object containing the conditions.
     * @return mixed The filtered result based on the provided conditions.
     */
    function where($where, $operator = self::OPERADORES['EQ'], $op_logic=self::OPERADORES_LOGICOS['AND'])
    {
        $clousureWhere = ['column' => $where, 'operator' => $operator];

        if(!in_array($operator, self::OPERADORES)){
            throw new \Exception("Operador inválido para cláusula WHERE.");
        }

        if (!in_array($op_logic, self::OPERADORES_LOGICOS)) {
            throw new \Exception("Operador inválido para cláusula WHERE.");
        }
        //verifica se já existe uma expressão where
        if (!empty($this->where['column'])) {

            $this->where['next']['clousere'] = $clousureWhere;
            $this->where['next']['operator'] = $op_logic;
        };

        $this->where = $clousureWhere;

        return $this;
        
    }


    /**
     * Formata a cláusula LIMIT para consultas SQL.
     *
     * @param int $offset O deslocamento inicial dos resultados (padrão é 0).
     * @param int $limit O número máximo de resultados a serem retornados.
     * @return string A cláusula LIMIT formatada para uso em SQL.
     */
    public function limit( $limit, $offset=0)
    {
        $this->limit = "LIMIT $offset, $limit";
        return $this;
    }

    public function  columns(array $columns_data){
        $columns_result = [];

        if($this->FuncColumnConstruct === self::OPTION_CONSTRUCT_COLUMN['defult']){
            return $columns_data;
        }
        
        foreach($columns_data as $data){
            $data['column'] = $this->columnsInColumn($data['column']);
            
            $columns_result[] = $this->{$data['fun']}($data['column'],$data['as']);

        }

        $this->columns = $columns_result;

        return $columns_result;
    }

    private function columnsInColumn($columnData){
        $columns_result = $columnData;

        while(isset($columnData['column'])) {
            $columns_result = $this->{$columnData['fun']}($columnData['column'], $columnData['as']);
            $columnData = $this->columnsInColumn($columnData['column']) ?? null;
        };

        return $columns_result;
    }

    public function orderBy(string $column=''){
        if(!$column)return $this;

        $this->order_by = "ORDER BY $column DESC";
    }

    public function groupBy(string $column)
    {
        if (empty($column)) return $this;

        $indexID = array_search('id', $this->columns);

        // if(!is_null($indexID)){
        //     if(!($column === 'id')){
        //         array_splice($this->columns,$indexID,1);
        //     }
        // }
        $this->group_by = "GROUP BY $column";
        return $this;
    }
}
