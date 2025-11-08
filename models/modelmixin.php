<?php
namespace Models;

use Database\QueryBuild;
use Dotenv\Parser\Value;
use Error;
use Models\ValidateMixin;
use Database\DB;
use Models\ColumnTrait;

/**
 * Mixin class providing getter and setter methods for model properties.
 */
class ModelMixin
{
    use ValidateMixin;

    protected $table, $assignedColumns, $columns;
    const OPERADORES = ['EQ' => '=', 'GT' => '>', 'LT' => '<', 'GTE' => '>=', 'LTE' => '<=', 'NEQ' => '<>', 'LIKE' => 'LIKE', 'IN' => 'IN', 'NOT IN' => 'NOT IN'];
    const OPERADORES_LOGICOS = ['AND' => 'AND', 'OR' => 'OR'];
    const DATASEARCH = 'id';
    private $dbconection = null;
    private $queryValues = [];
    private $columnsForQuery = [];
    protected $columnsRequired = [];
    protected $query;

    function __construct($data = [])
    {
        try{
            $this->dbconection =  DB::conectarBanco();
        }catch(ERROR $m){
            error_log($m);
        }

        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        if(trait_exists(ColumnTrait::class)){
            $option_construct_column = QueryBuild::OPTION_CONSTRUCT_COLUMN['personal'];
         }else{
            $option_construct_column = QueryBuild::OPTION_CONSTRUCT_COLUMN['defult'];
         }

        $this->query = new QueryBuild($this->table, $option_construct_column);
    }



    function get($paramether)
    {
        if (property_exists($this, $paramether)) {
            return $this->{$paramether};
        }
    }

    function set($paramether, $value)
    {
        if (!property_exists($this, $paramether)) return;
        if($paramether !== 'id') $this->assignedColumns[] = $paramether;
        $this->{$paramether} = $value;
        $this->queryValues[':' . $paramether] = $value;
        $this->columnsForQuery[$paramether] = $paramether;
    }

    function showAtribuits()
    {
        print_r(get_object_vars($this));
    }

    function all()
    {
        $query = $this->query->select($this->columns);

        return $this->executeQuery($query);
    }

    function find(array|null $dataSearch = [self::DATASEARCH],$meta=[])
    {   
        $query = $this->query->select($this->columns);
        $whereData = $this->filterDataForquery($this->columnsForQuery);

        return $this->executeQuery($query, $whereData);
    }

    public function columns(...$columns)
    {
        $this->columns = $this->query->columns($columns);

        return $this;
    }

    public function where($where, $operator = self::OPERADORES['EQ'], $op_logic= self::OPERADORES_LOGICOS['AND'])
    {
        $this->query->where($where, $operator, $op_logic);
        return $this;
    }

    public function groupBy(string $group)
    {
        $this->query->groupBy($group);
        return $this;
    }

    public function orderBy(string $column)
    {
        $this->query->orderBy($column);
        return $this;
    }

    public function limit(int $limit, int $offset=0)
    {
        $this->query->limit($limit, $offset);
        return $this;
    }


    function delete($dataSearch = [self::DATASEARCH],$_dropAll=false)
    {
        $componentQuery = new QueryBuild($this->table);

        if(!$_dropAll) $this->validateRequiredFields($this->columnsRequiredForMethods['delete']);
        
        $query = $componentQuery->delete();
        return $this->executeQuery($query, $this->queryValues);
    }

    function update($dataSearch = [self::DATASEARCH])
    {
        ['columns'=>$whereColumns] = $this->filterDataForquery($dataSearch);
        $whereData = $this->queryValues;
        $this->validateRequiredFields($this->columnsRequiredForMethods['update']);
        $componentQuery = new QueryBuild($this->table, $this->assignedColumns, $whereColumns);
        // $this->set('updated_at', new \DateTime()->format('Y-m-d H:i:s'));
        $query = $componentQuery->update();

        return $this->executeQuery($query, $whereData);
    }

    public function insert()
    {   
        $componentQuery = new QueryBuild($this->table);
        $whereData = $this->filterDataForquery($this->assignedColumns);
        $this->validateRequiredFields($this->columnsRequiredForMethods['create']);
        $query = $componentQuery->insert($this->assignedColumns);
        return $this->executeQuery($query, $whereData);
    }

    public function relationship(ModelMixin $classInstance){
        $instance_columns = $classInstance
            ->where('id',self::OPERADORES['EQ'])
            ->limit(1)
            ->find();
        
        return $instance_columns;
    }

    /**
     * Executa uma consulta SQL usando a conexão do banco
     * @param string $scriptSql Consulta SQL
     * @param array $params Parâmetros para consulta preparada
     * @return array|false Resultado da consulta ou false em caso de erro
     */
    public function executeQuery($scriptSql, $params = [])
    {
        if (!$this->dbconection) {
            throw new \Exception('Conexão com banco não estabelecida.');
        }
        try {
            $stmt = $this->dbconection->prepare($scriptSql);
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Filtra os dados para a consulta SQL
     * @param array $columns Colunas para filtrar
     * @return array array [dados para consulta, colunas para consulta]
     */
    public function filterDataForquery(array $columns){
        $dataQuery = [];

        foreach($columns as $paramether){
           $dataQuery[':'.$paramether] = $this->get($paramether);
        }

        return $dataQuery;
    }
}
