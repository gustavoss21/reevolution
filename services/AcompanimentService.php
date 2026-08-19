<?php

namespace Services;

use Services\Service;
use Models\GenerateColumn;
use Models\ModelMixin;
use Services\ManagerFilters;
use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\TagModel;

class AcompanimentService extends Service
{
    use GenerateColumn;

    public ManagerFilters $instanceFilters;

    private const RELATION_TYPE = ['PARENT' => 'parent', 'BROTHERS' => 'brothers', 'GRANDMOTHER' => 'grandmother', 'CHILD' => ''];

    public $tables = [
        'themes' => ThemeModel::class,
        'stages' => StageModel::class,
        'topics' => TopicModel::class,
        'tags'   => TagModel::class,
    ];

    public $tableIndexed = [];

    public $tableNamesTransleted = [
        'temas' => 'themes',
        'estagios' => 'stages',
        'topicos' => 'topics',
        'tags' => 'tags'
    ];

    public $resultSearch = [];

    public function __construct()
    {
        parent::__construct();
        $this->instanceFilters = new ManagerFilters();
    }

    function managerFilters(array $data): array
    {
        $this->instanceFilters->fromArray($data);
        $this->processConfiguredTables();

        return $this->buildRelationshipTree();
    }

    private function processConfiguredTables(): void
    {
        foreach ($this->instanceFilters->method_seted as $configuredTable) {
            $tableName     = key($configuredTable);
            $instanceTable = $this->setupInstancesTable(current($configuredTable), $tableName);

            if ($this->isBrokenChildren($tableName) || !($instanceTable instanceof ModelMixin)) {
                continue;
            }

            [$connections, $relationType] = $this->getConectionTable($tableName);
            
            if (!$connections) {
                $this->setDesassociateData($tableName, $instanceTable->find() ?: []);
                continue;
            }

            $this->processConnections($tableName, $instanceTable, $connections, $relationType);
        }
    }

    private function processConnections(
        string $tableName,
        ModelMixin $instanceTable,
        array $connections,
        string $relationType
    ): void {
        foreach ($connections as $parentTableName => $connection) {
            $columnName = key($connection);
            $data       = $this->getResultDataRelation($relationType, $columnName, clone $instanceTable, $connection);

            if (!$data) {
                $this->instanceFilters->methods_order[$tableName]['dropChildren'] = true;
                $this->dropDataSearch($parentTableName);
                continue;
            }

            if ($relationType === self::RELATION_TYPE['BROTHERS']) {
                $this->filterDataMetch($data, $columnName);
            }

            $this->AssociateData($tableName, $connection[$columnName], $data, [
                'table' => $parentTableName,
                'type'  => $relationType
            ]);
        }
    }

    private function buildRelationshipTree(): array
    {
        $resultList = $this->getDataSearch();
        if (!$resultList) {
            return [];
        }

        $relationOptions = [
            'relationships' => ['column_instance' => 'id', 'column_value' => ''],
            'childrens'     => ['column_instance' => '', 'column_value' => 'id']
        ];
        $relation    = current($relationOptions);
        $relationKey = key($relationOptions);

        while (true) {
            $tableName          = key($resultList);
            $tableData          = current($resultList);
            $tableRelations     = $this->instanceFilters->methods_order[$tableName];
            $configuredRelation = $tableRelations[$relationKey] ?? null;

            if (!$configuredRelation) {
                if (!next($relationOptions)) {
                    break;
                }
                $relationKey        = key($relationOptions);
                $relation           = current($relationOptions);
                $configuredRelation = $tableRelations[$relationKey] ?? null;
                if (!$configuredRelation) {
                    break;
                }
                $columnRelationName          = current($configuredRelation);
                $relation['column_instance'] = $columnRelationName;
                $relation['column_value']    = 'id';
            } else {
                $columnRelationName          = current($configuredRelation);
                $relation['column_instance'] = 'id';
                $relation['column_value']    = $columnRelationName;
            }

            $parentTableName = key($configuredRelation);
            unset($tableRelations[$relationKey]);
            $otherRelation = current(array_diff(array_keys($relationOptions), [$relationKey]));
            unset($this->instanceFilters->methods_order[$parentTableName][$otherRelation]);

            $key              = $relation['column_instance'];
            $dataParent[$key] = array_column($tableData, $relation['column_value']);
            $parent           = new $this->tables[$parentTableName];
            $parentResult     = $this->getResultDataRelation(self::RELATION_TYPE['PARENT'], $key, $parent, $dataParent);
            $this->setChildInParent($parentResult, $tableData, $tableName, $columnRelationName);

            $dataSet = [$parentTableName, $tableName];
            if ($relationKey === 'relationships') {
                $this->dropDataSearch($tableName);
                $dataSet = [$parentTableName];
            }

            $this->setDataSearch($parentResult, ...$dataSet);
            if (!next($resultList)) {
                $resultList = $this->getDataSearch();
            }
        }

        return $this->getDataSearch();
    }

    function setupInstancesTable(array $methods, string $tableName): ModelMixin|null
    {
        $table         = new $this->tables[$tableName];
        $instanceTable = null;

        foreach ($methods as $method => $argumentForMethod) {
            $instanceTable = $this->instanceFilters->$method($argumentForMethod, $table);
        }

        return $instanceTable;
    }

    function getConectionTable(string $tableName)
    {
        $conectionData  = [];
        $relationType   = '';
        $tablerelations = $this->instanceFilters->methods_order[$tableName]['relationships'] ?? [];

        foreach ($tablerelations as $tableRelated => $foreignKey) {
            $relations = $this->getDataSearch();
            $relation = $this->getDataSearch($tableRelated);

            if($relations){
                
            }
            $searchTable = key($relations) ?? '';

            [
                'relation' => $relationType,
                'data'     => $intersectionBetweenTables
            ] = $this->getRelationship($tableName, $searchTable);

            if (count($relations) > 0 && !$relation) {
                if (!$intersectionBetweenTables) continue;

                $table                 = key($intersectionBetweenTables);
                $columnName            = $intersectionBetweenTables[$table];
                $conectionData[$table] = [];
                $columnNameSearch      = 'id';

                if ($relationType === self::RELATION_TYPE['GRANDMOTHER']) {
                    $columnNameSearch = $columnName;
                    $columnName = 'id';
                }

                foreach ($this->getDataSearch($searchTable) as $instanceData) {
                    $tableInstance = new $this->tables[$table]([$columnNameSearch => $instanceData[$columnName]]);
                    $parentData    = $tableInstance->where($columnNameSearch)->find();
                    $columnNameRelation = $this->getColumnNameForTable($table);
                    $conectionData[$table][$columnNameRelation][] = $parentData[0]['id'];
                }

                next($intersectionBetweenTables);
            }



            if (!$relation) continue;

            $relationType = self::RELATION_TYPE['PARENT'];

            foreach ($relation as $relationData) {
                $conectionData[$tableRelated][$foreignKey] = $relationData['id'];
            }
        }

        return [$conectionData, $relationType];
    }

    function filterDataMetch(array $data, string $relationColumnName)
    {
        $resultSearch               = $this->getDataSearch();
        $this->dropDataSearch();

        foreach ($resultSearch as $tableName => $Datatable) {
            $dataResult  = [];

            foreach ($Datatable as $dataItem) {
                foreach ($data as $searchDataItem) {
                    if (!($searchDataItem[$relationColumnName] === $dataItem[$relationColumnName])) {
                        continue;
                    }

                    if (in_array($searchDataItem, $dataResult)) continue;

                    $dataResult[] = $searchDataItem;
                }
            }

            $this->setDataSearch($dataResult, $tableName);
        }
    }

    function AssociateData(string $tableName, array $dataRelation, array $dataTable, array $relationData)
    {
        ['table' => $relationTableName, 'type' => $relation] = $relationData;
        $resultMatchParent                                = [];
        $dataResultTables                                 = $this->getDataSearch();
        $dataResult                                       = current($dataResultTables);
        $dataResultKey                                    = key($dataResultTables);
        $columnName                                       = $this->getColumnNameForTable($relationTableName);
        $dataResultIds = array_map(
            fn($item) => $item[$columnName],
            $dataResult
        );

        $dataResultForquery = array_intersect($dataResultIds, $dataRelation);


        if (self::RELATION_TYPE['BROTHERS'] === $relation) {
            $table             = new $this->tables[$relationTableName](['id' => $dataResultForquery]);
            $resultMatchParent = $table->whereIN('id')->find();

            if (!$resultMatchParent) return;


            $this->dropDataSearch($dataResultKey);

            $this->setChildInParent($resultMatchParent, $dataTable, $tableName, $columnName);
            $this->setChildInParent($resultMatchParent, $dataResult, $dataResultKey, $columnName);
            $this->setDataSearch($resultMatchParent, $relationTableName);
        } elseif (self::RELATION_TYPE['GRANDMOTHER'] === $relation) {
            $parentTableName = $relationTableName;
            $childTableName = $tableName;
            $tableRelationships   = $this->instanceFilters->methods_order[$parentTableName]['relationships'];
            $grandmotherTableName = key($tableRelationships);
            $grandmotherData      = $this->getDataSearch($grandmotherTableName);
            $tableColumnName      = current($tableRelationships);
            $childColumnName      = current($this->instanceFilters->methods_order[$childTableName]['relationships']);

            $dataParentForseach = array_map(function ($item) {
                return $item['id'];
            }, $grandmotherData);

            $dataChildForseach = array_map(function ($item) use ($childColumnName) {
                return $item[$childColumnName];
            }, $dataTable);

            $instanceParent = new ($this->tables[$relationTableName])();
            $instanceParent->set($tableColumnName, $dataParentForseach);
            $instanceParent->set('id', $dataChildForseach);
            $instanceParent->whereIN($tableColumnName)->whereIN('id', 'AND');
            $resultSearch = $instanceParent->find();
            $this->dropDataSearch($grandmotherTableName);

            foreach ($grandmotherData as $parentKey => &$item) {
                $parentItem = [];
                foreach ($resultSearch as $key => $dataItem) {
                    if ($dataItem[$tableColumnName] === $item['id']) {

                        foreach ($dataTable as $keyChild => $childDataItem) {
                            if ($childDataItem[$childColumnName] === $dataItem['id']) {
                                array_splice($dataTable, $keyChild, 1);

                                if (array_key_exists($dataItem['id'], $parentItem)) {
                                    $parentItem[$dataItem['id']][$childTableName][] = $childDataItem;
                                    continue;
                                }
                                $parentItem[$dataItem['id']] = $dataItem;
                                $parentItem[$dataItem['id']][$childTableName][] = $childDataItem;
                            }
                        }
                    }
                }
                $item[$relationTableName] = $parentItem;

                $this->setDataSearch($item, $grandmotherTableName);
            };

            // $this->dropDataSearch($grandmotherTableName);
        } else {
            $this->setDataSearch($dataTable, $tableName, $relationTableName, key($dataRelation));
        }
    }

    function getColumnNameForTable(string $tableName)
    {
        $columnNameFormated = substr($tableName, 0, -1) . '_id';
        return $columnNameFormated;
    }

    function setDataChildren(array $childrens, array $parent, string $columnName)
    {
        foreach ($parent as $dataItem) {
            foreach ($childrens as $child) {
                $childTableName = key($childrens);

                if ($parent['id'] === $child[$columnName]) {
                    $parent[$childTableName][] = $child;
                }
            }
        }

        return $parent;
    }

    function &getDataSearch(?string $tableName = null)
    {
        $parent     = $this->tableIndexed[$tableName] ?? null;
        $this->stepList(null, $this->resultSearch);

        if (!$tableName) return $this->resultSearch;

        //parent
        if ($parent) {
            $instance = $this->dissectParent($parent);
            if (str_contains($parent, $tableName)) return $instance;
        }

        if ($tableName && !isset($this->stepList()[$tableName])) return [];

        return $this->stepList($tableName);
    }
    function setDataSearch(array $data, ?string $tableName = null, ?string $parent = null, ?int $parentKey = null)
    {
        $parent             = $this->tableIndexed[$parent] ?? $parent;

        //parent
        if (!$this->resultSearch || $this->resultSearch[$tableName]) {
            $dataForSearch = count($data) > 1 ? $data : $data[0];
            $dataForResultSearch = $this->resultSearch[$tableName] ?? [];
            $this->resultSearch[$tableName] = array_merge($dataForResultSearch, $dataForSearch);
            
            return;
        }

        if ($parent && $this->dissectParent($parent)) {
            // $this->resultSearch[$tableName] = $data;
        
            if ($parentKey !== null) {
                $this->stepList($parentKey);
                $parent .= ':' . $parentKey;
            }
            $this->tableIndexed[$tableName] = $parent . ':' . $tableName;
            
            
        } else {
            $this->stepList(null, $this->resultSearch);
        }

        if (!$tableName) return false;

        //brothers | grandmother
        $this->stepList()[$tableName] = $data;
        return true;
    }

    function dropDataSearch(?string $tableName = null)
    {

        if (!$tableName) {
            $this->tableIndexed             = [];
            $this->resultSearch = [];


            return;
        }

        unset($this->tableIndexed[$tableName]);
        unset($this->resultSearch[$tableName]);
    }


    function getRelationship(string $tableName, string $searchTable)
    {
        $methodOrderTable         = $this->instanceFilters->methods_order[$searchTable];
        $searchTableRelationship  = $methodOrderTable['relationships'] ?? [];
        $currentTableRelationship = [];
        $is_child = isset($methodOrderTable['childrens']);

        if (isset($this->instanceFilters->methods_order[$tableName])) {
            $methodOrderCurrentTable  = $this->instanceFilters->methods_order[$tableName];
            $currentTableRelationship = $methodOrderCurrentTable['relationships'] ?? [];
        }

        if (!$currentTableRelationship) {
            return ['relation' => self::RELATION_TYPE['CHILD'], 'data' => []];
        }


        // mesma linha - mesmo relacionado
        $intersectionBetweenTables = array_intersect($searchTableRelationship, $currentTableRelationship);

        if (isset($methodOrderTable['childrens']) && isset($methodOrderTable['childrens'][$tableName])) {
            $relationType = self::RELATION_TYPE['PARENT'];
            $intersectionBetweenTables = $methodOrderTable['childrens'];
        } elseif ($intersectionBetweenTables) {
            $relationType = self::RELATION_TYPE['BROTHERS'];
        } elseif ($is_child && isset($methodOrderTable['childrens'][$searchTable])) {
            $relationType                            = self::RELATION_TYPE['CHILD'];
            $intersectionBetweenTables[$searchTable] = $methodOrderTable['childrens'][$searchTable];
        } else {
            // dois patamar a abaixo - o relacionado acima tera uma relação acima
            $tableRelatino       = key($currentTableRelationship);
            $parentRelationships = $this->instanceFilters->methods_order[$tableRelatino]['relationships'] ?? [];

            if (!$tableRelatino || !$parentRelationships) {
                return ['relation' => self::RELATION_TYPE['CHILD'], 'data' => []];
            }

            $relationType                              = self::RELATION_TYPE['GRANDMOTHER'];
            $columnName                                = current($parentRelationships);
            $intersectionBetweenTables                 = $currentTableRelationship;
            $intersectionBetweenTables[$tableRelatino] = $columnName;
        }

        return ['relation' => $relationType, 'data' => $intersectionBetweenTables];
    }

    function &dissectParent(string $parent, int $index = 0)
    {

        $parents    = explode(':', $parent);
        $this->stepList(null, $this->resultSearch);

        foreach ($parents as $parentItem) {
            $data = $this->stepList();

            if (!isset($data[$parentItem]) && is_int(key($data))) {
                $this->stepList($index);
            }

            $this->stepList($parentItem);


            if (!$this->stepList()) return null;
        }

        return $this->stepList();
    }
          /**
     * Verifica se uma tabela possui relacionamentos quebrados (com dropChildren ativado)
     * 
     * @param string $tableName Nome da tabela a verificar
     * @return bool Retorna true se o relacionamento pai tem dropChildren ativado, false caso contrário
     */
    function isBrokenChildren(string $tableName)
    {
              // Obtém a configuração da tabela dos métodos ordenados
        $relationTable = $this->instanceFilters->methods_order[$tableName];

                // Verifica se a tabela possui relacionamentos definidos
        if (isset($relationTable['relationships'])) {
                    // Obtém o nome da tabela pai (primeira chave do array de relacionamentos)
            $parentTableName = key($relationTable['relationships']);
                // Obtém a configuração da tabela pai
            $relationParentTable = $this->instanceFilters->methods_order[$parentTableName];

                  // Verifica se a tabela pai está configurada para descartar filhos
            if ($relationParentTable['dropChildren'] ?? false) {
                return true;
            }
        }
        return false;
    }

    public function &stepList($index = null, &$data = null)
    {
        static $controller = [];
        static $count      = -1;

        if (is_null($index) && is_null($data)) return $controller[$count];

        if ($data) {
            $controller[] = &$data;
            $count++;
        };

        if ($index !== null && $count >= 0) {
            $controller[] = &$controller[$count][$index];
            $count++;
        }



        return $controller[$count];
    }

    function setDesassociateData(string $tableName, array $result)
    {

        $dataOrder = $this->instanceFilters->methods_order[$tableName];

        if (empty($result)) {
            $dataOrder['dropChildren'] = true;
            return;
        }

        $this->setDataSearch($result, $tableName);
    }

    function getResultDataRelation(string $relationType, string $columnName, ModelMixin $instanceTableReuse, array $instaceData)
    {

        if (!($relationType === self::RELATION_TYPE['GRANDMOTHER'])) {
            $instanceTableReuse->set($columnName, $instaceData[$columnName]);
            if (is_array($instaceData[$columnName])) {
                $instanceTableReuse->whereIN($columnName);
            } else {
                $instanceTableReuse->where($columnName);
                $instaceData[$columnName] = [$instaceData[$columnName]];
            }
        } elseif (!is_array($instaceData[$columnName])) {
            $instaceData[$columnName] = [$instaceData[$columnName]];
        }

        return $instanceTableReuse->find();
    }

    function setChildInParent(array &$resultMatchParent, array &$dataTable, string $ParentTableName, string $columnRelationName)
    {
        foreach ($resultMatchParent as &$dataItem) {

            foreach ($dataTable as $key => &$dataTableItem) {

                if ($dataItem['id'] === $dataTableItem[$columnRelationName]) {
                    $dataItem[$ParentTableName][] = $dataTableItem;
                    unset($dataTable[$key]);
                }
                
            }
        }
    }
}
