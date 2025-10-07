<?php
namespace Models;

use Database\QueryBuild;
use Dotenv\Parser\Value;
use Error;
use Models\ValidateMixin;
use Database\DB;

/**
 * Mixin class providing getter and setter methods for model properties.
 */
class ModelMixin
{
    use ValidateMixin;

    protected $table, $assignedColumns, $query, $columns;
    const OPERADORES = ['EQ' => '=', 'GT' => '>', 'LT' => '<', 'GTE' => '>=', 'LTE' => '<=', 'NEQ' => '<>', 'LIKE' => 'LIKE', 'IN' => 'IN', 'NOT IN' => 'NOT IN'];
    const OPERADORES_LOGICOS = ['AND' => 'AND', 'OR' => 'OR'];
    const DATASEARCH = 'id';
    private $dbconection = null;
    private $queryValues = [];
    private $columnsForQuery = [];
    protected $columnsRequired = [];

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

    function all($limit=null, $columns = null)
    {
        $querycomponets = new QueryBuild($this->table, $columns??$this->columns,'', ['meta'=>['limit'=>$limit]]);
        $query = $querycomponets->select();

        return $this->executeQuery($query);
    }

    function find(array $dataSearch = [self::DATASEARCH],$meta=[])
    {   

        ['data'=>$whereData, 'columns'=>$whereColumns] = $this->filterDataForquery($this->columnsForQuery);
        $query = (new QueryBuild($this->table, $whereColumns))
                    ->columns($this->columns,$meta['columns'])
                    ->where($meta['where']??'')
                    ->groupBy($meta['group']??'')
                    ->orderBy($meta['order']??'')
                    ->limit($meta['limit'] ?? '')
                    ->select();

        return $this->executeQuery($query, $whereData);
    }

    function delete($dataSearch = [self::DATASEARCH],$_dropAll=false)
    {
        $componentQuery = new QueryBuild($this->table, $this->columns, $dataSearch);

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
        $componentQuery = new QueryBuild($this->table, $this->assignedColumns);
        ['data'=>$whereData] = $this->filterDataForquery($this->assignedColumns);
        $this->validateRequiredFields($this->columnsRequiredForMethods['create']);
        $query = $componentQuery->insert();
        return $this->executeQuery($query, $whereData);
    }

    public function relationship(ModelMixin $classInstance){
        $instance_columns = $classInstance->columns;
        $query = (new QueryBuild($classInstance->table, $instance_columns,'id',[0,1]))->select();

        ['data' => $whereData] = $classInstance->filterDataForquery(['id']);
        return $this->executeQuery($query,$whereData);
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
        $dataQuery = ['data'=>[],'columns'=>[]];

        foreach($columns as $paramether){
           $dataQuery['data'][':'.$paramether] = $this->get($paramether);
            $dataQuery['columns'][] = $paramether;
        }

        return $dataQuery;
    }
}
