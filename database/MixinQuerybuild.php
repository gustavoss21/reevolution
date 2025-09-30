<?php

namespace Database;

/**
 * Class to build SQL queries dynamically.
 */
class MixinQuerybuild
{

    protected $table, $columns, $expressions, $limit;
    const OPERADORES = ['EQ' => '=', 'GT' => '>', 'LT' => '<', 'GTE' => '>=', 'LTE' => '<=', 'NEQ' => '<>', 'LIKE' => 'LIKE', 'IN' => 'IN', 'NOT IN' => 'NOT IN'];
    const OPERADORES_LOGICOS = ['AND' => 'AND', 'OR' => 'OR'];
    
    public function __construct(string $table, $columns = [], $where = 'id', array|null $limit = [])
    {
        $this->expressions = $this->buildWhere($where);
        $this->columns = $columns;
        $this->table = $table;
        $this->limit = $this->buildLimit($limit);
    }

    /**
     * Format parameters for SQL queries. 
     *
     * @param array $queryPartition The parameters to format.
     * @param string $separator The separator to use between parameters.
     * @return string The formatted string for SQL queries.
     */
    function formatParamts($queryPartition, $separator = ' ')
    {

        if (empty($queryPartition)) return null;

        $lastIndex = count($queryPartition) - 1;
        $result = '';

        foreach ($queryPartition as $i => $item) {
            $result .= $item;
            if ($i !== $lastIndex) {
                $result .= $separator;
            }
        }
        return $result;
    }

    /**
     * Format columns for SQL queries with placeholders.
     *
     * @param array $columns The columns to format.
     * @return string The formatted string for SQL queries.
     */
    function formatParamtsForValue(array $columns, $hascolumns = null)
    {
        if (empty($columns)) return '';

        $parameterFormated = '';
        $lastIndex = count($columns) - 1;

        foreach ($columns as $key => $value) {

            $column = $hascolumns ? $value . '= :' : ':';
            $parameterFormated .= $column . $value;
            if (!($lastIndex === $key)) {
                $parameterFormated .= ', ';
            }
        }
        return $parameterFormated;
    }

    function formatParamtsForWhere(array $expWhere)
    {
        if (empty($expWhere['expression'])) return '';

        $whereFormated = 'WHERE ' . $expWhere['expression']['column'] . ' ' . $expWhere['operator'] . ' :' . $expWhere['expression']['column'];

        if (isset($expWhere['expression']['next'])) {
            $nextWhere = $expWhere['expression']['next'];
            $whereFormated .= " {$expWhere['expression']['operator']} {$nextWhere['expression']['column']} {$nextWhere['operator']} :{$nextWhere['expression']['column']}";
        }

        return $whereFormated;
    }

    /**
     * Filters data based on specified conditions.
     *
     * @param mixed $data The data to be filtered, typically an array or object containing the conditions.
     * @return mixed The filtered result based on the provided conditions.
     */
    function buildWhere($data)
    {
        $where = ['expression' => [], 'operator' => self::OPERADORES['EQ']];
        if (empty($data)) return $where;

        if (!is_array($data)) {
            // Caso seja uma string, converte para o formato esperado
            $where['expression']['column'] = $data;
            return $where;
        } else {
            // Caso seja um array com operador definido, converte para o formato esperado
            $whereResult = $this->unpackWhere($data);


            return $whereResult;
        }
    }

    /**
     * Desempacota e processa os dados fornecidos para construir uma cláusula WHERE.
     *
     * @param mixed $data Os dados de entrada que serão utilizados para gerar a condição WHERE.
     * @return array Retorna um array representando a cláusula WHERE processada.
     */
    private function unpackWhere($data)
    {
        $where = ['expression' => [], 'operator' => $data['operator'] ?? self::OPERADORES['EQ']];

        foreach ($data as $key => $item) {
            if ($key === 'operator') {
                continue;
            }

            if (!empty($where['expression'])) {

                $nexWhere = ['expression' => [], 'operator' =>  self::OPERADORES['EQ']];

                $nexWhere['expression']['column'] = $item;


                if (is_array($item)) {
                    $nexWhere = $this->unpackWhere($item);
                }

                if (!isset($data['operator'])) {
                    $where['expression']['operator'] = self::OPERADORES_LOGICOS['AND'];
                } else {
                    $where['expression']['operator'] = in_array($data['operator'], self::OPERADORES_LOGICOS) ? $data['operator'] : self::OPERADORES_LOGICOS['AND'];
                }

                $where['expression']['next'] = $nexWhere;
                continue;
            };


            if (is_array($item)) {
                $where = $this->unpackWhere($item);
                continue;
            }

            $where['expression']['column'] = $item;
        }

        return $where;
    }

  
    /**
     * Formata a cláusula LIMIT para consultas SQL.
     *
     * @param int $offset O deslocamento inicial dos resultados (padrão é 0).
     * @param int $limit O número máximo de resultados a serem retornados.
     * @return string A cláusula LIMIT formatada para uso em SQL.
     */
    private function buildLimit($limit)
    {
        if(empty($limit))return'';

        [$offset, $limit] = $limit;

        return " LIMIT $offset, $limit";
    }

    public static function createQueryTimeline(){
        return '        SELECT 
            id,
            name,
            priority,
            domain_level,
            status,
            topic_id,
            updated_at,
            COUNT(*) AS amount_event,
            (priority * 1.5) 
              + (DATEDIFF(CURDATE(),updated_at) / 5.0) 
              + ((3 - domain_level) * 2) 
              + (2 + status) AS pontuacao
        FROM reevolutiondb.stages
        ORDER BY pontuacao DESC
        LIMIT 3;';
    }

    public static function createQueryGroupByStatus(){
        return 'SELECT 
                    status,
                    COUNT(*) AS amount_event,
                    AVG((priority * 1.5) 
                        + (DATEDIFF(CURDATE(), updated_at) / 5.0) 
                        + ((3 - domain_level) * 2) 
                        + (2 + status)) AS point_average
                FROM reevolutiondb.stages
                GROUP BY status
                "';
    }
}
