<?php

declare(strict_types=1);

namespace Services;

use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\TagModel;
use Models\ModelMixin;
use Models\RelationshipTopicAndTag;


class ManagerFilters{

    public array $method_seted;
    public array $data = [];
    public ?string $filterforTable;
    public ?string $orderTasksForDate;
    public ?string $filterForExpiredTime;
    public ?string $statusFilter;
    public ?bool $amountContentOfStudyFilter;
    public array $instancesTable = [];
    public array $orderForTree = [];
    const TREE_SKELETON = [
        0=>'themes', 
        1=>'topics',
        2=>'stages',
        3=>'tags'
    ];

    

    public array $tables = [
        'themes'      => ThemeModel::class,
        'stages'      => StageModel::class,
        'topics'      => TopicModel::class,
        'tags' => TagModel::class
        
    ];

    public array $tableNamesTransleted = [
        'temas' => 'themes',
        'estagios' => 'stages',
        'topicos' => 'topics',
        'tags' => 'tags'
    ];
    
    public array $methods_order = [
        'themes' => [
            'methods' => [
                'filterforTable',
            ],
            'childrens'=>[
                'topics' => 'topic_id'
            ]
        ],
        'topics'=>[
            'methods'=>[
            'amountContentOfStudyFilter',
            'filterforTable',
            'filterForExpiredTime',
            ],
            'dropChildren' => false,
            'relationships'=>[
                'themes' => 'theme_id'
            ],
            'childrens' => [
                'stages',
                'tags'
            ]

        ],

        'stages'=>[
            'methods'=>[
            'orderTasksForDate',
            'statusFilter',
            ],
            'relationships' => [
                'topics' => 'topic_id'
            ]
        ],
        'tags'=>[
            'methods'=>[
                'filterforTable',
            ],
            'relationships' => [
                'topics' => 'topic_id'
            ]
        ]
    ];

    /**
     * Cria a instância a partir de um array associativo
     */
    public function fromArray(array $data){
        foreach ($this->methods_order as $table => $dataForSearch) {
            foreach ($data as $functionData) {
                $functionName = @key($functionData);
                $argumentForFunction = @current($functionData);

                if(in_array($functionName, $dataForSearch['methods'])){
                    if (is_string($argumentForFunction) && str_contains($argumentForFunction, ':')) {
                        $tableData = $this->spellLabel($argumentForFunction);

                        if (!$tableData || $tableData['modelName'] !== $table) continue;

                        $this->method_seted[$tableData['modelName']][$functionName] = $tableData['valueSearch'];
                        continue;
                    }

                    $this->method_seted[$table][$functionName] = $argumentForFunction;
                }
        }
            
        }
    }

      /**
     * Interpreta a string de filtro e retorna o nome do modelo e o valor de busca
     *
     * @param string $dataPossible A string no formato "label:valor" para interpretar
     * @return array|null Retorna um array associativo com 'modelName' e 'valueSearch', ou null se o label for inválido
     */
    function spellLabel(string $dataPossible)
    {
        [$labelName, $valueSearch] = explode(':', $dataPossible);

        if (!array_key_exists($labelName, $this->methods_order)) {
            return null;
        }

        return [
            'modelName' => $labelName,
            'valueSearch' => $valueSearch
        ];
    }

    function filterforTable(string $valueSearch,ModelMixin $instanceModel)
    {
        $instanceModel->set('name', $valueSearch);
        $instanceModel->where('name');

        if($instanceModel->table === 'tags'){
            $tagResult = $instanceModel->find();
            
            if (!$tagResult) {
                return null;
            }

            $relationshipWithTopic = new RelationshipTopicAndTag(['tag_id' => $tagResult[0]['id']]);
            $relationshipWithTopic->where('tag_id');
            $instanceModel = $relationshipWithTopic;

        }
        

        return $instanceModel;
    }

    function orderTasksForDate(string $order, ModelMixin $table)
    {
        if (!in_array($order, ['asc', 'desc'])) {
            return null;
        }

        $table->orderBy('updated_at', $order);
        return $table;
    }

    /**
     * Filters records based on the expiration status of a term
     *
     * @param string $term The 'expired' key that determines the filter direction
     * @return object Returns a database query object filtered by end_date comparison
     *
     * @description
     * If the term is marked as expired, returns records where end_date is less than the current date and time.
     * Otherwise, returns records where end_date is greater than the current date and time.
     */
    function filterForExpiredTime(string $term , ModelMixin $table)
    {
        $date_current = date('Y-m-d H:i:s');
        $table->set('end_date', $date_current);

        if ($term == 'expired') {
            return $table->where('end_date', '<');
        }

        return $table->where('end_date', '>');
    }

    /**
     * Filters records by status condition
     *
     * This method applies a WHERE clause to the table query based on the provided status parameter.
     * By default, it sets the status to -1 (future) and uses an equality operator.
     * If the provided status differs from the 'future' option (-1), it switches to a not-equal operator instead.
     *
     * @param string $status The status value to filter by. Use -1 for future status, or any other value for non-future status.
     *
     * @return object Returns the table object with the WHERE clause applied, allowing for method chaining.
     */
    function statusFilter(string $status, ModelMixin $table)
    {
        $statusOptions = ['started' => 0, 'future' => -1];
        $table->set('status', -1);
        $operator = $table::OPERADORES['EQ'];

        if ($statusOptions[$status] != $statusOptions['future']) {
            $operator = $table::OPERADORES['NEQ'];
        }

        return $table->where('status', $operator);
    }

    function amountContentOfStudyFilter(bool $isLot, ModelMixin $table)
    {
        $table->set('lot_to_discuss', $isLot);
        return $table->where('lot_to_discuss', $table::OPERADORES['EQ']);
    }

    function statedStudy(){}

   
        
}

