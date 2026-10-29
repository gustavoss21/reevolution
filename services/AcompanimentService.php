<?php

namespace Services;

use Services\Service;
use Models\GenerateColumn;
use Models\ModelMixin;
use Services\ManagerFilters;
use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\RelationshipTopicAndTag;

class AcompanimentService extends Service
{
    use GenerateColumn;

    public ManagerFilters $instanceFilters;

    private const RELATION_TYPE = ['PARENT' => 'parent', 'BROTHERS' => 'brothers', 'GRANDMOTHER' => 'grandmother', 'CHILD' => 'child', 'GRANDCHILD' => 'grandchild', 'NONE' => ''];

    public $tables = [
        'themes' => ThemeModel::class,
        'stages' => StageModel::class,
        'topics' => TopicModel::class,
        'tags'   => RelationshipTopicAndTag::class,
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

        foreach ($this->instanceFilters->method_seted as $tableName=> $configuredTable) {
            // $tableName     = key($configuredTable);
            $instanceTable = $this->setupInstancesTable($tableName, $configuredTable);

            if ($this->isBrokenChildren($tableName) || !($instanceTable instanceof ModelMixin)) {
                continue;
            }

            [$connections, $relationType] = $this->getConectionWithRoot($tableName);
            
            if (!$connections) {
                $this->preprocessingForWhereIn($instanceTable);
                $data     = $instanceTable->find();
                   
                if( !$data) {
                    $this->setDesassociateData($tableName);
                    continue;
                }

                $this->setRootData($data, $tableName);
                continue;
            }

            $this->processConnections($instanceTable, $connections, $relationType);
        }
    }

    private function processConnections(
        ModelMixin $instanceTable,
        array $connections,
        string $relationType
    ): void {
        foreach ($connections as $parentTableName => $connection) {
            $columnName = key($connection);
            $instance   = clone $instanceTable;
            $tableName = $instance->table;
            $instance->set($columnName, $connection[$columnName]);
            $this->setCustomWhere($columnName, $instance, $connection);
            $data     = $instance->find();
            if (!$data) {
                $this->instanceFilters->methods_order[$tableName]['dropChildren'] = true;
                $this->dropDataSearch($parentTableName);//est
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

        $relationKey = '';
        
        while (true) {
            $tableName  = key($resultList);
            $dataResult = &$resultList[key($resultList)];
            
            if ($tableName === null || $dataResult === null) {
                break;
            }

             $configRelation = $this->getConfigRelation( $tableName, $dataResult, true);

            if ($configRelation === null) {
                break;
            }

            [
                'columnRelationName' => $columnRelationName,
                'key'                => $key,
                'parent'             => $instanceParent,
                'relationType'         => $relationKey
            ] = $configRelation;

            $this->setCustomWhere(
                $key,
                $instanceParent,
                [$key=>$instanceParent->get($configRelation['key'])]
            ) ?? [];

            $parentResult     = $instanceParent->find();

            if (!$parentResult) {
                break;
            }

            if($relationKey === self::RELATION_TYPE['PARENT']){
                $parentResultFormated = [$instanceParent->table => $parentResult];
                $this->setChildInParent($dataResult, $parentResult, $instanceParent->table, $columnRelationName);
                // $parentResultFormated = [$instanceParent->table => &$dataResult[key($dataResult)][$instanceParent->table]];
                $this->buildRelationshipTree($parentResultFormated, true);
            }else if ($relationKey !== self::RELATION_TYPE['PARENT']) {
                $this->dropDataSearch($configRelation['tableName']);
                $this->setRootData($parentResult, $instanceParent->table);
                $this->setChildInParent($this->resultSearch[$configRelation['parent']->table], $dataResult, $tableName, $columnRelationName);
            } else {
                if($parentResult){
                    $this->setRootData($parentResult, $instanceParent->table, $tableName);
                }
            }
        }

        if (!$forRoot) {
            $this->setRootRemaining();
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

        $optionKeyFamily = null;
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
            'parent'             => $instanceParent,
            'relationType'         => $optionKeyFamily
        ];
    }

    function setupInstancesTable(string $tableName, array $methods): ModelMixin|null
    {
        $instanceTable         = new $this->tables[$tableName];

        foreach ($methods as $method => $argumentForMethod) {
            $instanceTable = $this->instanceFilters->$method($argumentForMethod, $instanceTable);
        }

        return $instanceTable;
    }

    function getConectionWithRoot(string $tableName)
    {
        $conectionData  = [];
        $relationType   = '';
        $tablerelations = $this->instanceFilters->methods_order[$tableName]['relationships'] ?? [];

        foreach ($tablerelations as $tableRelated => $foreignKey) {
            $relations = $this->getDataSearch();
            $relation = $this->getDataSearch(key($foreignKey));

            $searchTable = key($relations) ?? '';

            if (empty($searchTable)|| $searchTable===$tableName) continue;

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
                $dataRoot = $relations[$searchTable];

                if ($relationType === self::RELATION_TYPE['GRANDMOTHER']) {
                    $columnNameSearch = $columnName;
                    $columnName = 'id';
                }

                foreach ($dataRoot as $instanceData) {
                    $tableInstance = new $this->tables[$table]([$columnNameSearch => $instanceData[$columnName]]);
                    $parentData    = $tableInstance->where($columnNameSearch)->find();
                    $columnNameRelation = $this->getColumnNameForTable($table);
                    $conectionData[$table][$columnNameRelation] = array_column($parentData, 'id');
                }
            }

            foreach ($relation as $relationData) {
                if(!is_array($relationData['id'])) $relationData['id'] = [$relationData['id']];

                $conectionData[key($foreignKey)][current($foreignKey)] = $relationData['id'];//obs: add current
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

            $this->setRootData($dataResult, $tableName);
        }
    }

    function AssociateData(string $tableName, array $dataRelation, array $dataTable, array $relationData)
    {
        ['table' => $relationTableName, 'type' => $relation] = $relationData;
        $resultMatchParent                                = [];
        $dataRoot                                 = $this->getDataSearch();
        $dataResult                                       = current($dataRoot);
        $tableRoot                                    = key($dataRoot);
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


            $this->dropDataSearch($tableRoot);
            $this->setChildInParent($resultMatchParent, $dataTable, $tableName, $columnName);
            $this->setChildInParent($resultMatchParent, $dataResult, $tableRoot, $columnName);
            $this->setRootData($resultMatchParent, $relationTableName);

        } elseif (self::RELATION_TYPE['GRANDMOTHER'] === $relation) {
            $parentTableName = $relationTableName;
            $childTableName = $tableName;
            $tableRelationships   = $this->instanceFilters->familyOrder[$parentTableName]['relationships']['parent'];
            $grandmotherTableName = key($tableRelationships);
            $grandmotherData      = $dataRoot[$tableRoot];
            $tableColumnName      = current($tableRelationships);
            $childColumnName      = current($this->instanceFilters->familyOrder[$childTableName]['relationships']['parent']);

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
                unset($this->instanceFilters->methods_order[$tableName]['relationships']['parent']);
                unset($this->instanceFilters->methods_order[$parentTableName]['relationships']['parent']);
                unset($this->instanceFilters->methods_order[$parentTableName]['relationships']['child'][$tableName]);
                unset($this->instanceFilters->methods_order[$tableRoot]['relationships']['child']);
                $this->setRootData([$item], $grandmotherTableName);
            };

            // $this->dropDataSearch($grandmotherTableName);
        } else {
            $this->setRootData($dataTable, $tableName, $relationTableName, key($dataRelation));
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
        if (!$tableName || empty($this->resultSearch)) return $this->resultSearch;

        $parent = $this->tableIndexed[$tableName] ?? null;
        $this->stepList(null, $this->resultSearch);

        //parent
        if ($parent) {
            $instance = $this->dissectParent($parent);
            if (str_contains($parent, $tableName)) return $instance;
        }

        foreach($this->instanceFilters->tableNamesTransleted as $table){
            $dataCheck = key($this->stepList());

            if($table === $dataCheck && ($tableName !== $table)){
                $this->stepList($table);
            }elseif($this->stepList($tableName)){
                return $this->stepList();
            }

        }
        // if ($tableName && !$this->stepList($tableName)){
        //     $data = &$this->stepList(0);

        //     if($data && isset($data[$tableName]))return  $data[$tableName];

            return [];
        // };

      
    }
    function setRootData(array $data, ?string $tableName = null, ?string $parent = null, ?int $parentKey = null)
    {
        $parent             = $this->tableIndexed[$parent] ?? $parent;

        //parent
        if (!$this->resultSearch || $this->resultSearch[$tableName]) {
            $dataForSearch = count($data) > 0 ? $data : $data[0]; //acho que devo mudar para: $data[0]['id']
            $dataForResultSearch = $this->resultSearch[$tableName] ?? [];
            $this->resultSearch[$tableName] = array_merge($dataForResultSearch, $dataForSearch);
            
            return false;
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
        $methodOrderTable         = $this->instanceFilters->familyOrder[$searchTable]['relationships'] ?? [];
        $currentTableRelationship = $this->instanceFilters->familyOrder[$tableName]['relationships'] ?? [];
        $dataRelationship            = [];
        $intersectionBetweenTables = [];
        $relationType = '';
        
        foreach ($currentTableRelationship as $relationType => $relations) {
            foreach ($methodOrderTable as $otherRelationType => $otherRelations) {
                $intersectionBetweenTables = array_intersect($relations, $otherRelations);
                $otherRelationTypeFormated      = strtoupper($otherRelationType);
                
                if(empty($intersectionBetweenTables)){
                    $child  = $currentTableRelationship['child'];
                    $parent = $currentTableRelationship['parent'];
                    return $this->getRelationshipMother($child, $parent, $searchTable);
                      
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
            if ($relationParentTable['dropChildren']) {
                return true;
            }
        }
        return false;
    }

          /**
     * Mantém o controle de navegação por etapas em uma estrutura multidimensional.
     *
     * A função acumula referências em uma pilha interna para permitir a navegação
     * sequencial por índices e sub-arrays, mantendo o estado atual acessível por
     * referência. É útil para percorrer dados organizados em níveis hierárquicos.
     *
     * @param mixed|null $index Índice do nível atual que deve ser acessado. Quando
     *                          informado, o ponteiro interno é movido para esse
     *                          elemento, caso exista.
     * @param mixed|null $data Dados do nível atual a serem registrados na pilha.
     *                         Quando informado, é adicionada uma referência do
     *                         valor atual ao controle interno.
     * @return array Referência ao estado atual da navegação, ou a estrutura
     *                       vazia caso o índice solicitado não exista.
     */
    public function &stepList($index = null, &$data = null)
    {
        static $controller = [];
        static $count      = -1;

        if (is_null($index) && is_null($data)) return $controller[$count];

        if ($data) {
            $controller[] = &$data;
            $count++;
        };

        if ($index !== null ) {
            if(!isset($controller[$count][$index])){
                if(isset($controller[$count][0][$index])){
                    $controller[] = &$controller[$count][0][$index];
                    $count++;
                    return $controller[$count];
                }
                return [];
            }

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

      /**
     * Obtém o nome da coluna de relacionamento para uma tabela específica.
     *tem um ERROR elseif
     */
    function setCustomWhere(?string $columnName, ModelMixin &$instanceAgency, array $instaceData)
    {    
        // $instanceAgency->set($columnName, $instaceData[$columnName]);?????????
        if (is_array($instaceData[$columnName])) {
            $instanceAgency->whereIN($columnName);
        } else {
            $instanceAgency->where($columnName);
            $instaceData[$columnName] = [$instaceData[$columnName]];
        }
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

    function getRelationshipMother(?array $child, ?array $parent, string $searchTable)
    {
        $relationType = '';

        if($child || $parent){
                
            if($child){
                $child = key($child);
                $data  = $this->instanceFilters->familyOrder[$child]['relationships']['child'] ?? null;

                if(in_array($searchTable, array_keys( $data))){
                    $relationType                   = self::RELATION_TYPE['GRANDMOTHER'];
                    $dataIntersection[$child] = $data[$searchTable];
                }else{
                        $data = [];
                }
            }elseif($parent){
                $parent       = key($parent);
                $data         = $this->instanceFilters->familyOrder[$parent]['relationships']['parent'] ?? null;
                
                if(in_array($searchTable, array_keys( $data))){
                    $relationType                   = self::RELATION_TYPE['GRANDMOTHER'];
                    $dataIntersection[$parent] = $data[$searchTable];
                }else{
                        $data = [];
                }
            }
        }
        
    
        return ['relation' => $relationType, 'data' => $dataIntersection ?? []];

    }

      /**
     * Preprocessa os dados para a consulta WHERE IN, verificando se há apenas uma coluna atribuída na instância.
     *
     * @param ModelMixin $instanceAgency A instância do modelo que contém os dados a serem processados.
     */
    function preprocessingForWhereIn( ModelMixin &$instanceAgency){
       
        $columnNames = $instanceAgency->assignedColumns;
        $columnName  = '';
        $instaceData = [];

        if(empty($columnNames)){
            return;
        }

        foreach ($columnNames as $columnName) {
            $data = $instanceAgency->get($columnName);
            
            if(is_array($data)){
                $instanceAgency->clearQuery($columnName);
            }

            $instaceData = [$columnName => $data];
            $this->setCustomWhere($columnName, $instanceAgency, $instaceData);
        }
    }

    function getAncestors(string $table){
        $tree = $table;

        while(true){
            $relation = $this->instanceFilters->familyOrder[$table]['relationships']['parent'];

                if(empty($relation))break;
                
                $table = key($relation);

                $tree = $table.':'.$tree;
        }

        return $tree;
    }

    function setRootRemaining(){
        foreach($this->instanceFilters->methods_order as $table => &$relations){

            $relations = &$relations['relationships'];

            foreach($relations as $typeRelation =>&$relation){
                $table = key($relation);

                if(empty($table))continue;
                
                $parents = &$this->dissectParent($this->getAncestors($table));
                
                foreach($parents as $parentData){
                    $column = current($relation);
                    // $columnOfInstance = 'child' === $typeRelation?'id':$column;
                    $parentsFormated = new $this->tables[$table]([$column=>$parentData['id']]);
                    $dataPrecess = $parentsFormated->find();
                    $this->setChildInParent($parents, $dataPrecess, $table, $column);
                    unset($relation);
                    unset($this->instanceFilters->methods_order[$table]['relationships']['parent']);
                }
            }

        }
    }
}
