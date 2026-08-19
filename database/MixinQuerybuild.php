<?php

namespace Database;

use Models\ColumnTrait;

/**
 * Class to build SQL queries dynamically.
 */
class MixinQuerybuild 
{
    use ColumnTrait;

    protected array $columns = [];
    protected array $where   = [];
    protected ?string $limit = null;
    private mixed $meta;
    protected string $order_by = '';
    protected string $group_by = '';
    public array $valueWhere = [];
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

        if (empty($queryPartition)) return '*';

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
        if( count($this->where) < 1) return '';

        $whereFormated = 'WHERE ';
        $count         = 0;
        $hasInOperator = false;

        foreach($this->where as $whereItem){
            $operator_logic = $whereItem['op_logic'] ?? '';
            $column         = $whereItem['column'];
            $value = ':' .  $column;

            $operator_logic = (empty($operator_logic) ? self::OPERADORES_LOGICOS['AND'] : $operator_logic);

            if($count <= 0) {
                $operator_logic = '';
            }

            if(!empty($operator_logic)) $operator_logic = ' '. $operator_logic . ' ' ;

            if($whereItem['operator'] == self::OPERADORES['IN']){
                  // $value              = preg_replace('/\d{2}/','?', $this->valueWhere[$column]);
                // $countValue = is_array($this->valueWhere[$column])? count($this->valueWhere[$column]) : 1;
                $value              =  '('. implode(', ',array_fill(0,count($this->valueWhere[$column]),'?')).')';
                $this->valueWhere[] = $this->valueWhere[$column];
                unset($this->valueWhere[$column]);
                $hasInOperator = true;
            }


            $whereFormated .= $operator_logic . $column . ' ' . $whereItem['operator'] .' '. $value;
            $count++;
        }
        if($hasInOperator){
            $whereFormated = preg_replace('/:\w+/','?',$whereFormated);
        }
        
        return $whereFormated;
    }

    /**
     * Filters data based on specified conditions.
     *
     * @param mixed $where The conditions to filter by, typically an array or object.
     * @param string $operator The SQL operator to use for the condition.
     * @param string|null $op_logic The logical operator to use between conditions.
     * @return self The instance of the class for method chaining.
     */
    function where($where, $operator, $op_logic=null)
    {

        //verifica se o operador é válido
        if(!in_array($operator, self::OPERADORES)){
            throw new \Exception("Operador inválido para cláusula WHERE.");
        }

        $clousureWhere = ['column' => $where, 'operator' => $operator, 'op_logic' => $op_logic];


        //definer o primeiro where 
        $this->where[] = $clousureWhere;

        return $this;
        
    }


    /**
     * Formata a cláusula LIMIT para consultas SQL.
     *
     * @param int $offset O deslocamento inicial dos resultados (padrão é 0).
     * @param int $limit O número máximo de resultados a serem retornados.
     * @return self A cláusula LIMIT formatada para uso em SQL.
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

    private function columnsInColumn(mixed $columnData){
        $columns_result = $columnData;

        while(isset($columnData['column'])) {
            $columns_result = $this->{$columnData['fun']}($columnData['column'], $columnData['as']);
            $columnData = $this->columnsInColumn($columnData['column']) ?? null;
        };

        return $columns_result;
    }

    public function orderBy(string $column='', string $direction = 'DESC'){
        if(!$column)return $this;

        $this->order_by = "ORDER BY $column $direction";
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
