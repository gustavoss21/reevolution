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
        $whereWith = $this->formatParamtsForWhere($this->where);
        return "DELETE FROM {$this->table} $whereWith";

    }

    function update(){
        $whereWith = $this->formatParamtsForWhere($this->where);
        $columns = $this->formatParamtsForValue($this->columns, true);
        return "UPDATE {$this->table} SET  $columns $whereWith";
    }
    
    function insert(){
        $columns = $this->formatParamts($this->columns,', ');
        $columnValues = $this->formatParamtsForValue($this->columns);
        return "INSERT INTO {$this->table} ($columns) VALUES ($columnValues)";
    }
}