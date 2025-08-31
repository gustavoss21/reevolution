<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/database/conectiondb.php';
require dirname(__DIR__) . '/database/migrationMixins.php';
/**
 * Class to build SQL queries dynamically.
 */
class Querybuild extends MixinQuerybuild{

    function select(){
        $columns = $this->formatParamts($this->columns,', ');
        $whereWith = $this->formatParamtsForWhere($this->where);
        return "SELECT {$columns} FROM {$this->table} $whereWith";
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