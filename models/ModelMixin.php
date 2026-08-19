<?php
namespace Models;

use Database\QueryBuild;
use Models\ValidateMixin;
use Database\DB;
use Models\ColumnTrait;

  /**
 * Mixin class providing getter and setter methods for model properties.
 */
class ModelMixin
{
    use ValidateMixin;

    protected array $assignedColumns = [];
    public array $columns;
    protected array $columnsWhere    = [];
    public string $table;
    const   OPERADORES         = ['EQ' => '=', 'GT' => '>', 'LT' => '<', 'GTE' => '>=', 'LTE' => '<=', 'NEQ' => '<>', 'LIKE' => 'LIKE', 'IN' => 'IN', 'NOT IN' => 'NOT IN'];
    const   OPERADORES_LOGICOS = ['AND' => 'AND', 'OR' => 'OR'];
    const   DATASEARCH         = 'id';
    private \PDO $dbconection;
    private array $queryValues       = [];
    private array $columnsForQuery   = [];
    protected array $untitledColumn = [];
    protected array $columnsRequired   = [];
    protected QueryBuild $query;
    static array $LABELS;
    public array $dbLog;

    function __construct($data = [])
    {
        try {
            $this->dbconection = DB::conectarBanco();
        } catch (\Exception $m) {
            error_log($m);
        }

        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        $optionKey = trait_exists(ColumnTrait::class) ? 'personal' : 'defult';
        $option_construct_column = \Database\MixinQuerybuild::OPTION_CONSTRUCT_COLUMN[$optionKey];

        $this->query = new QueryBuild($this->table, $option_construct_column);
    }

    public function setDataDefault() {}

    function get(string $paramether): mixed
    {
        if (property_exists($this, $paramether)) {
            return $this->{$paramether};
        }

        return null;
    }

    function set(string $paramether, mixed $value): void
    {
        if (!property_exists($this, $paramether) || is_null($value)) return;
          // if($paramether === 'id')return; 
        $this->assignedColumns[]              = $paramether;
        $this->{$paramether}                  = $value;
        $this->queryValues[':' . $paramether] = $value;
        $this->columnsForQuery[$paramether]   = $paramether;
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

    function find()
    {
        $query       = $this->query->select($this->columns);
        $columnsData = $this->filterDataForquery($this->assignedColumns ?? []);
        $whereData   = $this->filterDataForquery($this->columnsWhere ?? []);
        $queryData   = array_merge($columnsData, $whereData);

        if(key($whereData) == 0) $queryData = $whereData;

        return $this->executeQuery($query, $queryData);
    }

    public function columns(array...$columns)
    {
        $this->columns = $this->query->columns($columns);

        return $this;
    }

    public function where(string $where,string $operator = self::OPERADORES['EQ'])
    {
        if ($operator === self::OPERADORES['LIKE']) {
            $this->set($where, $this->get($where) . '%');
        }
        $this->columnsWhere[] = $where;
        $this->query->where($where, $operator);
        return $this;
    }

    public function whereAnd(string $where, string $operator = self::OPERADORES['EQ'])
    {
        if ($operator === self::OPERADORES['LIKE']) {
            $this->set($where, $this->get($where) . '%');
        }
        $this->columnsWhere[] = $where;
        $this->query->where($where, $operator, self::OPERADORES_LOGICOS['AND']);
        return $this;
    }

    public function whereOr(string $where, string $operator = self::OPERADORES['EQ'])
    {
        if ($operator === self::OPERADORES['LIKE']) {
            $this->set($where, $this->get($where) . '%');
        }
        $this->columnsWhere[] = $where;
        $this->query->where($where, $operator, self::OPERADORES_LOGICOS['OR']);
        return $this;
    }

    public function whereIN(string $where, string $operatorLogic = self::OPERADORES_LOGICOS['AND'])
    {
        $this->columnsWhere[]            = $where;
        $this->$where                    = $this->$where;
        $this->query->valueWhere[$where] = $this->$where;
        $operator                        = self::OPERADORES['IN'];
        $this->untitledColumn[]          = $where;
        $this->query->where($where, $operator, $operatorLogic);
        return $this;
    }

    public function groupBy(string $group)
    {
        $this->query->groupBy($group);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'DESC')
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function limit(int $limit, int $offset = 0)
    {
        $this->query->limit($limit, $offset);
        return $this;
    }


    function delete($dataSearch = [self::DATASEARCH], $_dropAll = false)
    {
        $componentQuery = new QueryBuild($this->table);

        if (!$_dropAll) $this->validateRequiredFields($this->columnsRequiredForMethods['delete']);

        $query = $componentQuery->delete();
        return $this->executeQuery($query, $this->queryValues);
    }

    function update($dataSearch = [self::DATASEARCH])
    {
        ['columns' => $whereColumns] = $this->filterDataForquery($dataSearch);
        $whereData                   = $this->queryValues;
        $this->validateRequiredFields($this->columnsRequiredForMethods['update']);
        $componentQuery = new QueryBuild($this->table);
        // $componentQuery = new QueryBuild($this->table, $this->assignedColumns, $whereColumns);
          // $this->set('updated_at', new \DateTime()->format('Y-m-d H:i:s'));
        $query = $componentQuery->update();

        return $this->executeQuery($query, $whereData);
    }

    public function insert()
    {
        $componentQuery = new QueryBuild($this->table);
        $whereData      = $this->filterDataForquery($this->assignedColumns);
        $this->validateRequiredFields($this->columnsRequiredForMethods['create']);
        $query = $componentQuery->insert($this->assignedColumns);
        return $this->executeQuery($query, $whereData);
    }

    public function relationship(ModelMixin $classInstance)
    {
        $instance_columns = $classInstance
            ->where('id', self::OPERADORES['EQ'])
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
            $this->logErrorDb($e->getMessage());
            return false;
        }
    }

      /**
     * Filtra os dados para a consulta SQL
     * @param array $columns Colunas para filtrar
     * @return array array [dados para consulta, colunas para consulta]
     */
    public function filterDataForquery(array $columns)
    {
        $dataQuery       = [];
        $dataQueryUntled = [];
        $doUntliled = false;

        foreach ($columns as $paramether) {
            $data = $this->get($paramether);

            if (is_null($data) || !property_exists($this, $paramether)) continue;

            if(in_array($paramether,$this->untitledColumn)){
                $doUntliled = true;
            }

            $dataQuery[':' . $paramether] = $data;
            $dataQueryUntled[] = is_array($data)?$data:[$data];
        }

        $dataQuery = $doUntliled? array_merge(...$dataQueryUntled): $dataQuery;

        return $dataQuery;
    }

    public function slug(string $string)
    {
          // Converte para minúsculas
        $slug = strtolower($string);

          // Remove acentuação
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);

          // Troca qualquer coisa que não seja letra/número por hífen
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

          // Remove hífens extras no começo/fim
        $slug = trim($slug, '-');

        return $slug;
    }

    public function getForm($without_columns = [])
    {
        $columns_f = "";

        foreach (static::$LABELS as $key => $_) {
            if (!in_array($key, $this->columns)) continue;
            if (in_array($key, $without_columns)) continue;

            $columns_f .= "'$key', ";
        };
        $columns_f = rtrim($columns_f, ', ');
        $query     = "SELECT 
                    column_name,
                    data_type,
                    character_maximum_length,
                    column_default,
                    is_nullable
                  FROM information_schema.columns
                  WHERE table_name = :table and COLUMN_NAME in ($columns_f);";
          // return $query;
        return $this->executeQuery($query, [':table' => $this->table]);
    }

    public function __clone(){
        $this->query = clone $this->query;
    }
}
