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

    private const RELATION_TYPE = ['PARENT' => 'parent', 'BROTHERS' => 'brothers', 'GRANDMOTHER' => 'grandmother', 'CHILD' => 'child', 'GRANDCHILD' => 'grandchild', '' => ''];

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

        return $this->buildRelationshipTree($this->resultSearch);
    }

    private function processConfiguredTables(): void
    {
        if(!isset($this->instanceFilters->method_seted))return;

        foreach ($this->instanceFilters->method_seted as $configuredTable) {
            $tableName     = key($configuredTable);
            $instanceTable = $this->setupInstancesTable(current($configuredTable), $tableName);

            if ($this->isBrokenChildren($tableName) || !($instanceTable instanceof ModelMixin)) {
                continue;
            }

            [$connections, $relationType] = $this->getConectionTable($tableName);
            
            if (!$connections) {
                $data = $instanceTable->find();
                if( !$data) {
                    $this->setDesassociateData($tableName);
                    continue;
                }

                $this->setDataSearch($data, $tableName);
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

    private function buildRelationshipTree(array &$resultList, bool $forRoot=false): array
    {
        if (!$resultList) {
            return [];
        }

        $relationOptions = [
            'parent'    => ['column_instance' => 'id', 'column_value' => ''],
            'grandMother'    => ['column_instance' => 'id', 'column_value' => ''],
            'child' => ['column_instance' => '', 'column_value' => 'id']
        ];

        $relation    = current($relationOptions);
        $relationKey = key($relationOptions);
        $dataResult   = &$resultList[key($resultList)];

        while (true) {
            $tableName = key($resultList);
            if ($tableName === null) {
                break;
            }

            $configRelation = $this->getConfigRelation( $tableName, $dataResult, true);

            if ($configRelation === null) {
                  // next($dataResult);
                  // continue;
                return $dataResult;
            }

            [
                'columnRelationName' => $columnRelationName,
                'key'                => $key,
                'parent'             => $instanceParent
            ] = $configRelation;

            $parentResult = $this->getResultDataRelation(
                self::RELATION_TYPE['PARENT'],
                $key,
                $instanceParent,
                [$key=>$instanceParent->get($configRelation['key'])]
            ) ?? [];

            if (!$parentResult) {
                next($dataResult);
                continue;
            }

           
            $dataRelation = $instanceParent->get($key);

            $this->setChildInParent($dataResult, $parentResult, $instanceParent->table, $columnRelationName);
             $parentResultFormated = [$instanceParent->table => &$dataResult[key($dataResult)][$instanceParent->table]];

            $this->buildRelationshipTree($parentResultFormated, true);

            if ($relationKey !== self::RELATION_TYPE['PARENT']) {
                $this->dropDataSearch($configRelation['tableName']);
                $this->setDataSearch($parentResult, $configRelation['parentTableName']);
            } else {
                if($parentResult){
                    $this->setDataSearch($parentResult, $instanceParent->table, $tableName);
                }
            }

        }

        return $this->getDataSearch();
    }

    function dropRelationships(string $tableName)
    {
        $this->instanceFilters->methods_order[$tableName]['dropChildren'] = true;
    }

      /**
     * Obtém a configuração de relacionamento para uma tabela específica.
     *
     * @param string $tableName Nome da tabela para a qual obter a configuração de relacionamento.
     * @param bool $dropRelation Indica se o relacionamento deve ser removido após a obtenção da configuração.
     * @return array{'columnRelationName': string, 'key': string, 'parent': object}|null Retorna um array com as informações de relacionamento ou null se não houver configuração.
     */
    function getConfigRelation(
        string $tableName,
        array $tableData = [],
        bool $dropRelation = false
    ): ?array {

        $relationKey = null;
        $relation    = null;

        if (empty($tableData)) {
            return null;
        }

        $columnRelationName = null;
        $familyTableName    = null;
        $tableRelations     = &$this->instanceFilters->methods_order[$tableName]['relationships'] ?? [];
        $optionsRelations   = ['parent', 'child'];
        
        foreach ($tableRelations as $optionKey => $option) {
            $configuredRelation = $tableRelations[$optionKey] ?? [];

            if ($configuredRelation) {
                $relationKey        = $optionKey;
                $relation           = $option;
                $familyTableName    = key($configuredRelation);
                $columnRelationName = current($configuredRelation);
                $optionKeyFamily    = current(array_diff($optionsRelations, [$optionKey]));

                if($dropRelation){
                     unset($tableRelations[$optionKey][$familyTableName]);
                     unset($this->instanceFilters->methods_order[$familyTableName]['relationships'][$optionKeyFamily]);
                     
                     if(!$tableRelations[$optionKey]){
                        unset($tableRelations[$optionKey]);
                     }
                }

                break;
            }
        }

        if (!$configuredRelation) {
            return null;
        }

        if (!isset($this->tables[$familyTableName])) {
            return null;
        }

        if ($relationKey === self::RELATION_TYPE['PARENT']) {
            $key         = 'id';
            $columnValue = $columnRelationName;
        } else {
            $key         = $columnRelationName;
            $columnValue = 'id';
        }

        $dataParent = [
            $key => array_values(array_unique(array_column($tableData, $columnValue)))
        ];
        if (!$dataParent[$key]) {
            return null;
        }

        $instanceParent = new $this->tables[$familyTableName]($dataParent);

        return [
            'columnRelationName' => $columnRelationName,
            'key'                => $key,
            'parent'             => $instanceParent
        ];
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
            $relation = $this->getDataSearch(key($foreignKey));

            $searchTable = key($relations) ?? '';

            if (empty($searchTable)) continue;

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
        if (!$tableName) return $this->resultSearch;

        $parent = $this->tableIndexed[$tableName] ?? null;
        $result = current($this->resultSearch);
        $this->stepList(null, $result);

        //parent
        if ($parent) {
            $instance = $this->dissectParent($parent);
            if (str_contains($parent, $tableName)) return $instance;
        }

        if ($tableName && !isset($this->stepList()[$tableName])){
            $data = &$this->stepList(0);

            if($data && isset($data[$tableName]))return  $data[$tableName];

            return [];
        };

        return $this->stepList($tableName);
    }
    function setDataSearch(array $data, ?string $tableName = null, ?string $parent = null, ?int $parentKey = null)
    {
        $parent             = $this->tableIndexed[$parent] ?? $parent;

        //parent
        if (!$this->resultSearch || $this->resultSearch[$tableName]) {
            $dataForSearch = count($data) > 0 ? $data : $data[0]; //acho que devo mudar para: $data[0]['id']
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

        $listCurrent = &$this->dissectParent($parent);
        //brothers | grandmother
        $listCurrent[0][$tableName] = $data;
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
        $methodOrderTable         = $this->instanceFilters->methods_order[$searchTable]['relationships'] ?? [];
        $currentTableRelationship = $this->instanceFilters->methods_order[$tableName]['relationships'] ?? [];
        $dataRelationship            = [];
        $intersectionBetweenTables = [];
        $relationType = '';
        
        foreach ($currentTableRelationship as $relationType => $relations) {
            foreach ($methodOrderTable as $otherRelationType => $otherRelations) {
                $intersectionBetweenTables = array_intersect($relations, $otherRelations);
                $relationTypeFormated      = strtoupper($relationType);
                $otherRelationTypeFormated      = strtoupper($otherRelationType);
                
                if(empty($intersectionBetweenTables)){
                    $child  = $currentTableRelationship['child'];
                    $parent = $currentTableRelationship['parent'];
                    ['relation' => $relationType, 'data' => $dataRelationship] = $this->getRelationshipMother($child, $parent, $searchTable);
                      
                };

                foreach ($intersectionBetweenTables as $table => $column) {
                    if ($table === $searchTable) {//parent or child
                        
                        $relationType              = self::RELATION_TYPE[$otherRelationTypeFormated];
                        $dataRelationship[$searchTable] = current($intersectionBetweenTables);
                        break 3;
                    }else{
                        $relationType = self::RELATION_TYPE['BROTHERS'];
                        $dataRelationship[$searchTable] = current($intersectionBetweenTables);
                    }
                }
            
            }
        }

        
        return ['relation' => $relationType, 'data' => $dataRelationship];
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
            $parentTableName = key(current($relationTable['relationships']));
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

    function setDesassociateData(string $tableName, ?string $tableFamily=null, $typeFamily=null)
    {
        $this->dropDataSearch($tableName);
        $this->dropRelationships($tableName);

        if ($tableFamily) {
            $this->dropRelationships($tableFamily);
        }
        $dataOrder = $this->instanceFilters->methods_order[$tableName];
        $dataOrder['dropChildren'] = true;
    }

    function getResultDataRelation(string $relationType, string $columnName, ModelMixin $instanceTableReuse, array $instaceData)
    {

        if (!($relationType === self::RELATION_TYPE['GRANDMOTHER'])) {
            // $instanceTableReuse->set($columnName, $instaceData[$columnName]);?????????

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

    function setChildInParent(array &$resultMatchParent, array &$dataTableChild, string $ParentTableName, string $columnRelationName)
    {
        foreach ($resultMatchParent as &$dataItem) {

            foreach ($dataTableChild as $key => &$dataTableItem) {

                if ($dataItem['id'] === $dataTableItem[$columnRelationName]) {
                    $dataItem[$ParentTableName][] = $dataTableItem;
                    unset($dataTableChild[$key]);
                }
                
            }
        }
    }

    function getRelationshipMother(?array $child, ?array $parent, string $searchTable,bool $next=false)
    {
        $relationType = '';

        if($child || $parent){
                
            if($child){
                $child = key($child);
                $data  = $this->instanceFilters->methods_order[$child]['relationships']['child'] ?? null;

                if(in_array($searchTable, array_keys( $data))){
                    $relationType                   = self::RELATION_TYPE['GRANDMOTHER'];
                    $dataIntersection[$child] = $data[$searchTable];
                }else{
                        $data = [];
                }
            }elseif($parent){
                $parent       = key($parent);
                $data         = $this->instanceFilters->methods_order[$parent]['relationships']['parent'] ?? null;
                
                if(in_array($searchTable, array_keys( $data))){
                    $relationType                   = self::RELATION_TYPE['GRANDCHILD'];
                    $dataIntersection[$parent] = $data[$searchTable];
                }else{
                        $data = [];
                }
            }
        }
        
    
        return ['relation' => $relationType, 'data' => $dataIntersection ?? []];

    }

}
