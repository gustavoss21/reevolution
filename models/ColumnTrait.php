<?php

namespace Models;
use Models\ColumnEnum;

trait ColumnTrait{
    private $column, $as, $operator;
    private $columns_partial = [];

    public function columns($column, $as=null){
        $this->column = $column;
        $this->as = $as;
    }

    public function funcColumns($columns,$as, ColumnEnum $operator){
        $this->columns_partial = $columns;
        $this->as = $as;
        $this->operator = $operator->value;
    }

    private function formateColumnMax($meta_column, &$columns)
    {
        $index = array_search($meta_column, $columns);
        unset($columns[$index]);

        $column = "max($meta_column)";
        $column_data = ['column' => $column, 'as' => ' AS ' . $meta_column];

        return $column_data;
    }

    private function formateColumnCount($data)
    {
        return ['column' => "COUNT({$data[0]})", 'as' => " AS {$data['as']}"];
    }

    private function formateAverangeColumn($meta_column, array &$columns)
    {

        if (!$meta_column['as']) throw new \BadFunctionCallException();

        $as = $meta_column['as'];

        unset($meta_column['as']);

        $columnMaxFormated = $this->formateColumnsMore($meta_column, $columns)['column'];
        $column = "AVG($columnMaxFormated)";
        $column_data = ['column' => $column, 'as' => ' AS ' . $as];

        return $column_data;
    }

    private function formateColumnsMore($meta_columns, &$columns)
    {
        $columns_result = [];

        foreach ($meta_columns as $meta_column) {
            $updated_at_diff = '(DATEDIFF(CURDATE(),updated_at) / 5.0)';

            $index = array_search($meta_column, $columns);
            if ($index) unset($columns[$index]);

            if (isset($$meta_column)) {
                $columns_result[] = $$meta_column;
            } else {
                $columns_result[] = $meta_column;
            }
        }

        $column = implode(' + ', $columns_result);
        $column_data = ['column' => $column, 'as' => ' AS ' . $columns_result[0]];

        return  $column_data;
    }


}