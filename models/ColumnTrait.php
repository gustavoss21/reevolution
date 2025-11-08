<?php

namespace Models;
use Models\FuncColumnInterface;

trait ColumnTrait{
    private $column, $as, $operator;
    private $columns_partial = [];
    static $parcial_column = [
        'updated_at_diff' => 'DATEDIFF(CURDATE(),updated_at)'
    ];

    public function GENERIC($column){
        $this->column = $column;
        return $column;
    }

    public function MAX($meta_column,$as=null)
    {
        $column = "max($meta_column)";
        $column .= $as ? ' AS ' . $as : '';

        return $column;
    }

    public function COUNT($data, $as=null)
    {
        $column = "COUNT({$data}) ";
        $column .= $as ? ' AS ' . $as : '';


        return $column;
    }

    public function AVERANGE($column, $as)
    {
        $column = is_array($column)? $this->MORE($column): $column;
        $column_result = "AVG($column) AS $as";

        return $column_result;
    }

    public function MORE($columns, $as = null)
    {
        $parcial_column = static::$parcial_column;

        $column = array_map(function ($column_item) use ($parcial_column) {
            if (isset($parcial_column[$column_item])) {
                return $parcial_column[$column_item];
            } else {
                return $column_item;
            }
        }, $columns);

        $as = $as ? ' AS ' . $as : '';
        $column_result = implode(' + ',$column) . $as; 


        return $column_result;
    }

    public function MIN($meta_column, $as = null)
    {
        $column = "min($meta_column)";
        $column .= $as ? ' AS ' . $as : '';

        return $column;
    }

}