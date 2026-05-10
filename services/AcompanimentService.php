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

    private const RELATION_TYPE = ['PARENT'=>'parent','BROTHERS'=>'brothers', 'GRANDMOTHER'=>'grandmother'];

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

    //chama os metodos de filtro dinamicamente
    function managerFilters(array $data){
        
        $this->instanceFilters->fromArray($data);

        foreach ($this->instanceFilters->method_seted as $tableName => $methods) {

            $instanceTable = $this->setupInstancesTable($methods,$tableName);

            if($this->isBrokenChildren($tableName))continue;
            if(!($instanceTable instanceof ModelMixin))continue;

            [$relationshipTableData, $relationType] = $this->getConectionTable($tableName);

            if(empty($relationshipTableData)){ 

                $result = $instanceTable->find();

                if(empty($result)) {
                    $this->instanceFilters->methods_order[$tableName]['dropChildren'] = true;
                    continue;
                }

                $this->setDataSearch($result, $tableName);

                continue;
            };

            foreach ($relationshipTableData as $instaceData) {
                $instanceTableReuse = clone $instanceTable;
                $parentTableName    = key($relationshipTableData);
                $columnName         = key($instaceData);
                
                if(!($relationType === self::RELATION_TYPE['GRANDMOTHER'])){
                    $instanceTableReuse->set($columnName, $instaceData[$columnName]);
                    if (is_array($instaceData[$columnName])) {
                        $instanceTableReuse->whereIN($columnName);
                    } else {
                        $instanceTableReuse->where($columnName);
                        $instaceData[$columnName] = [$instaceData[$columnName]];
                    }
                }elseif (!is_array($instaceData[$columnName])) {
                    $instaceData[$columnName] = [$instaceData[$columnName]];
                }
               
                $data_result = $instanceTableReuse->find();

                if(!$data_result){
                    $this->instanceFilters->methods_order[$tableName]['dropChildren'] = true;
                    continue;
                }

                if ($relationType === self::RELATION_TYPE['BROTHERS']) {
                    $this->filterDataMetch($data_result, $columnName);
                }

                  // $this->setDataSearch(, $tableName );
                $data_relation = ['table'=> $parentTableName,'type'=> $relationType];
                $this->AssociateData($tableName,$instaceData[$columnName],$data_result, $data_relation);
            }
        }

        return $this->getDataSearch();
    }

    function setupInstancesTable(array $methods, string $tableName): ModelMixin|null {
        $table     = new $this->tables[$tableName];
        $instanceTable = null;

        foreach ($methods as $method => $argumentForMethod) {
            $instanceTable = $this->instanceFilters->$method($argumentForMethod, $table);
        }

        return $instanceTable;

    
    }

    function getConectionTable(string $tableName){
        $conectionData  = [];
        $relationType   = '';
        $tablerelations = $this->instanceFilters->methods_order[$tableName]['relationships'];
        
        foreach ($tablerelations as $tableRelated => $foreignKey) {
            $relations = $this->getDataSearch();
            $relation = $this->getDataSearch($tableRelated);

            $searchTable = key($relations)??'';

            [
                'relation' => $relationType,
                'data'     => $intersectionBetweenTables
            ] = $this->getRelationship($tableName, $searchTable);

            if (count($relations) > 0 && !$relation) {
                if(!$intersectionBetweenTables)continue;
                
                $table                 = key($intersectionBetweenTables);
                $columnName            = $intersectionBetweenTables[$table];
                $conectionData[$table] = [];
                $columnNameSearch      = 'id';
                
               if($relationType === self::RELATION_TYPE['GRANDMOTHER']){
                  $columnNameSearch = $columnName;
                  $columnName = 'id';
                }
                
                foreach($this->getDataSearch($searchTable) as $instanceData){
                    $tableInstance = new $this->tables[$table]([$columnNameSearch => $instanceData[$columnName]]);
                    $parentData    = $tableInstance->where($columnNameSearch)->find();
                    $columnNameRelation = $this->getColumnNameForTable($table);
                    $conectionData[$table][$columnNameRelation][] = $parentData[0]['id'];
                }

                next($intersectionBetweenTables);
                
            }
            

            
            if(!$relation) continue;

            $relationType = self::RELATION_TYPE['PARENT'];

            foreach($relation as $relationData){
                $conectionData[$tableRelated][$foreignKey] = $relationData['id'];
            }

        }
        
        return [$conectionData, $relationType];
    }

    function filterDataMetch(array $data, string $relationColumnName)
    {
        $resultSearch               = $this->getDataSearch();
        $this->dropDataSearch();
        
        foreach ($resultSearch as $tableName =>$Datatable) {
            $dataResult  = [];
            
            foreach ($Datatable as $dataItem) {
                foreach ($data as $searchDataItem) {
                    if (!($searchDataItem[$relationColumnName] === $dataItem[$relationColumnName])) {
                        continue;
                    }

                    if(in_array($searchDataItem, $dataResult)) continue;

                    $dataResult[] = $searchDataItem;

                }
            }

            $this->setDataSearch($dataResult, $tableName);
        }
    }

    function AssociateData(string $tableName,array $dataRelation,array $dataTable, array $relationData){

        $resultMatchParent = [];
        ['table'=> $relationTableName,'type'=> $relation] = $relationData;
        
        if(self::RELATION_TYPE['BROTHERS'] === $relation){
            $table             = new $this->tables[$relationTableName](['id'=> $dataRelation]);
            $resultMatchParent = $table->whereIN('id')->find();
            
            if(!$resultMatchParent) return;

            foreach($resultMatchParent as &$dataItem){

                foreach($dataTable as &$dataTableItem){
                    $columnName = $this->getColumnNameForTable($relationTableName);

                    if ($dataItem['id'] === $dataTableItem[$columnName]) {
                        $dataItem[$tableName][] = $dataTableItem;
                    }
                }
                    
            }

            $this->setDataSearch($resultMatchParent, $relationTableName);
        }
        elseif(self::RELATION_TYPE['GRANDMOTHER'] === $relation){
            $parentTableName = $relationTableName;
            $childTableName = $tableName;
            $tableRelationships   = $this->instanceFilters->methods_order[$parentTableName]['relationships'];
            $grandmotherTableName = key($tableRelationships);
            $grandmotherData      = $this->getDataSearch($grandmotherTableName);
            $tableColumnName      = current($tableRelationships);
            $childColumnName      = current($this->instanceFilters->methods_order[$childTableName]['relationships']);

            $dataParentForseach = array_map(function ($item) {
                return $item['id'];
            },$grandmotherData);

            $dataChildForseach = array_map(function ($item)use($childColumnName) {
                return $item[$childColumnName];
            }, $dataTable);

            $instanceParent = new ($this->tables[$relationTableName])();
            $instanceParent->set($tableColumnName, $dataParentForseach);
            $instanceParent->set('id', $dataChildForseach);
            $instanceParent->whereIN($tableColumnName)->whereIN('id', 'AND');
            $resultSearch = $instanceParent->find();

            foreach($grandmotherData as $parentKey => $item ){
                foreach($resultSearch as $key => $dataItem){
                    if($dataItem[$tableColumnName] === $item['id']){
                        // $item[$relationTableName] = $dataItem;
                        
                        foreach ($dataTable as $childDataItem) {
                            if($childDataItem[$childColumnName] === $dataItem['id']){
                                $item[$relationTableName][$childTableName][] = $childDataItem;
                                $resultSearch[$key][$childTableName][] = $childDataItem;
                            }
                        }
                    }
                }
                $this->setDataSearch($resultSearch, $parentTableName, $grandmotherTableName, $parentKey);
            };

            // $this->dropDataSearch($grandmotherTableName);
        }else{
            $this->setDataSearch($dataTable, $tableName, $relationTableName,key($dataRelation));
        }
    }

    function getColumnNameForTable(string $tableName){
        $columnNameFormated = substr($tableName,0, -1) . '_id';
        return $columnNameFormated;        
    }

    function setDataChildren(array $childrens, array $parent,string $columnName){
        foreach($parent as $dataItem){
            foreach($childrens as $child){
                $childTableName = key($childrens);
                
                if($parent['id'] === $child[$columnName]){
                    $parent[$childTableName][] = $child;
                }
            }
        }

        return $parent;
    }

    function &getDataSearch(?string $tableName=null){
        $parent     = $this->tableIndexed[$tableName] ?? null;
        $this->stepList(null, $this->resultSearch);

        if (!$tableName) return $this->resultSearch;

        //parent
        if ($parent) {
            $instance = $this->dissectParent($parent);
            if(str_contains($parent, $tableName)) return $instance;
        }

        if($tableName && !isset($this->stepList()[$tableName])) return [];

        return $this->stepList($tableName);
    }
    function setDataSearch(Array $data, ?string $tableName = null, ?string $parent = null, ?int $parentKey = null){
        $parent             = $this->tableIndexed[$parent]?? $parent;

          //parent
        if(!$this->resultSearch){
            $this->resultSearch[$tableName] = $data;
            return;
        }

        if($parent){
            $this->dissectParent($parent);
            if($parentKey !== null){
                $this->stepList($parentKey);
                $parent .= ':' . $parentKey;
            }
            $this->tableIndexed[$tableName] = $parent.':' . $tableName;
        }else{
            $this->stepList(null, $this->resultSearch); 
        }

        if(!$tableName)return false;

        //brothers | grandmother
        $this->stepList()[$tableName] = $data;
        return true;

    }

    function dropDataSearch(?string $tableName = null){

        if(!$tableName){
            $this->tableIndexed             = [];
            $this->resultSearch = [];


            return;
        }

        unset($this->tableIndexed[$tableName]);
        unset($this->resultSearch[$tableName]);
    }
    
    
    function getRelationship(string $tableName, string $searchTable){
       $methodOrderTable         = $this->instanceFilters->methods_order[$searchTable];
       $searchTableRelationship  = $methodOrderTable['relationships']??[];
       $methodOrderCurrentTable  = $this->instanceFilters->methods_order[$tableName];
       $currentTableRelationship = $methodOrderCurrentTable['relationships']??[];

        // mesma linha - mesmo relacionado
        $intersectionBetweenTables = array_intersect($searchTableRelationship, $currentTableRelationship);

        if($methodOrderTable['childrens'] && isset($methodOrderTable['childrens'][$tableName])){
            $relationType = self::RELATION_TYPE['PARENT'];
            $intersectionBetweenTables = $methodOrderTable['childrens'];
        }
        elseif($intersectionBetweenTables) {
            $relationType = self::RELATION_TYPE['BROTHERS'];
        }
        else{
            // dois patamar a abaixo - o relacionado acima tera uma relação acima
            $relationType              = self::RELATION_TYPE['GRANDMOTHER'];
            $tableRelatino             = key($currentTableRelationship);
            $columnName                = current($this->instanceFilters->methods_order[$tableRelatino]['relationships']);
            $intersectionBetweenTables = $currentTableRelationship;
            $intersectionBetweenTables[$tableRelatino] = $columnName;
            
        }

        return ['relation' => $relationType, 'data' => $intersectionBetweenTables];
    }

    function &dissectParent(string $parent, int $index = 0){
           
           $parents    = explode(':', $parent);
           $this->stepList(null, $this->resultSearch);
            
            foreach($parents as $parentItem){
                $data = $this->stepList();

                if(!isset($data[$parentItem]) && is_int(key($data))){
                    $this->stepList($index);
                }

                $this->stepList($parentItem);
                
                
                if(!$this->stepList()) return null;
                
                }

           return $this->stepList();
    }
    function isBrokenChildren(string $tableName)
    {

        $relationTable = $this->instanceFilters->methods_order[$tableName]['relationships'];

        if ($relationTable) {
            $parentTableName     = key($relationTable);
            $relationParentTable = $this->instanceFilters->methods_order[$parentTableName];

            if ($relationParentTable['dropChildren']) {
                return true;
            }
        }
        return false;
    }

    public function &stepList($index = null,&$data = null){
        static $controller = [];
        static $count      = -1;
        
        if(is_null($index) && is_null($data))return $controller[$count];
         
        if ($data) {
            $controller[] = &$data;
            $count++;
        };
        
        if($index !== null && $count >= 0){
            $controller[] = &$controller[$count][$index];
            $count++;
        }

        
        
        return $controller[$count];
    }
}
