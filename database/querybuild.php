<?php
namespace Database;
require dirname(__DIR__) . '/database/mixindb.php';
/**
 * Class to build SQL queries dynamically.
 */
class Querybuild extends MixinQuerybuild{

    function select(){
        $columns = $this->formatParamts($this->columns,', ');
        $whereWith = $this->formatParamtsForWhere($this->expressions);
        return "SELECT {$columns} FROM {$this->table} $whereWith";
    }

    function delete(){
        $whereWith = $this->formatParamtsForWhere($this->expressions);
        return "DELETE FROM {$this->table} $whereWith";

    }

    function update(){
        $whereWith = $this->formatParamtsForWhere($this->expressions);
        $columns = $this->formatParamtsForValue($this->columns, true);
        return "UPDATE {$this->table} SET  $columns $whereWith";
    }
    
    function insert(){
        $columns = $this->formatParamts($this->columns,', ');
        $columnValues = $this->formatParamtsForValue($this->columns);
        return "INSERT INTO {$this->table} ($columns) VALUES ($columnValues)";
    }
}