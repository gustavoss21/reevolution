<?php
namespace Database;

use  Database\MixinQuerybuild;
/**
 * Class to build SQL queries dynamically.
 */
class QueryBuild extends MixinQuerybuild{

    function select($columns){
        $columns = $this->formatParamts($this->columns?? $columns,', ');
        $whereWith = $this->formatParamtsForWhere();
        return "SELECT {$columns} FROM {$this->table} $whereWith {$this->group_by} {$this->order_by} {$this->limit} ";
    }

    function delete(){
        $whereWith = $this->formatParamtsForWhere();
        return "DELETE FROM {$this->table} $whereWith";

    }

    function update(){
        $whereWith = $this->formatParamtsForWhere();
        $columns = $this->formatParamtsForValue($this->columns, true);
        return "UPDATE {$this->table} SET  $columns $whereWith";
    }
    
    function insert($columns){
        $query_columns = $this->formatParamts($columns,', ');
        $query_column_value = $this->formatParamtsForValue($columns);
        return "INSERT INTO {$this->table} ($query_columns) VALUES ($query_column_value)";
    }
}