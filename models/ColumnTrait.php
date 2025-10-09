<?php

namespace Models;
use Models\FuncColumnInterface;

trait ColumnTrait{
    private $column, $as, $operator;
    private $columns_partial = [];

    public function fomatedColumnsGeneric($column){
        $this->column = $column;
        return $column;
    }

    public function formateColumnMax($meta_column,$as=null)
    {
        $column = "max($meta_column)";
        $column .= $as ? ' AS ' . $as : '';

        return $column;
    }

    public function formateColumnCount($data, $as=null)
    {
        $column = "COUNT({$data[0]}) ";
        $column .= $as ? ' AS ' . $as : '';


        return $column;
    }

    public function formateAverangeColumn($column, $as)
    {
        $columnMaxFormated = $this->formateColumnsMore($column);
        $column_result = "AVG($columnMaxFormated) AS $as";

        return $column_result;
    }

    public function formateColumnsMore($columns, $as = null)
    {
        $parcial_column = [
            'updated_at_diff' => '(DATEDIFF(CURDATE(),updated_at) / 5.0)'
        ];

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

}