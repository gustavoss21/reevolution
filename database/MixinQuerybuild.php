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

    function formatParamtsForWhere($expWhere=[])
    {
        if (empty($expWhere['expression'])) return '';

        $whereFormated = 'WHERE ' . $expWhere['expression']['column'] . ' ' . $expWhere['operator'] . ' :' . $expWhere['expression']['column'];

        if (isset($expWhere['expression']['next'])) {
            $nextWhere = $expWhere['expression']['next'];
            $whereFormated .= " {$expWhere['expression']['operator']} {$nextWhere['expression']['column']} {$nextWhere['operator']} :{$nextWhere['expression']['column']}";
        }

        return $whereFormated;
    }

    /**
     * Filters data based on specified conditions.
     *
     * @param mixed $data The data to be filtered, typically an array or object containing the conditions.
     * @return mixed The filtered result based on the provided conditions.
     */
    function where($data='')
    {
        $this->where = ['expression' => [], 'operator' => self::OPERADORES['EQ']];

        if (empty($data)){
            return $this;
        }

        if (!is_array($data)) {
            // Caso seja uma string, converte para o formato esperado
            $this->where['expression']['column'] = $data;
            return $this;
        } else {
            // Caso seja um array com operador definido, converte para o formato esperado
            $this->where = $this->unpackWhere($data);
            return $this;
        }
    }

    /**
     * Desempacota e processa os dados fornecidos para construir uma cláusula WHERE.
     *
     * @param mixed $data Os dados de entrada que serão utilizados para gerar a condição WHERE.
     * @return array Retorna um array representando a cláusula WHERE processada.
     */
    private function unpackWhere($data)
    {
        $where = ['expression' => [], 'operator' => $data['operator'] ?? self::OPERADORES['EQ']];

        foreach ($data as $key => $item) {
            if ($key === 'operator') {
                continue;
            }

            if (!empty($where['expression'])) {

                $nexWhere = ['expression' => [], 'operator' =>  self::OPERADORES['EQ']];

                $nexWhere['expression']['column'] = $item;


                if (is_array($item)) {
                    $nexWhere = $this->unpackWhere($item);
                }

                if (!isset($data['operator'])) {
                    $where['expression']['operator'] = self::OPERADORES_LOGICOS['AND'];
                } else {
                    $where['expression']['operator'] = in_array($data['operator'], self::OPERADORES_LOGICOS) ? $data['operator'] : self::OPERADORES_LOGICOS['AND'];
                }

                $where['expression']['next'] = $nexWhere;
                continue;
            };


            if (is_array($item)) {
                $where = $this->unpackWhere($item);
                continue;
            }

            $where['expression']['column'] = $item;
        }

        return $where;
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
                $column_formated = $this->{$data['fun']}($data['column'],$data['as']);

                $columns_result[] = $column_formated;
        }

        $this->columns = $columns_result;

        return $columns_result;
    }

    public function orderBy(string $column=''){
        if(!$column)return $this;

        $this->order_by = "ORDER BY $column DESC";
    }

    public function groupBy(string $column='')
    {
        if (!$column) return $this;
        $indexID = array_search('id', $this->columns);
        if(!is_null($indexID)){
            if(!($column === 'id')){
                array_splice($this->columns,$indexID,1);
            }
        }
        $this->group_by = "GROUP BY $column";
        return $this;
    }
}
