<?php

namespace Services;

use Services\Service;
use Models\ThemeModel;
use Models\StageModel;
use Models\TopicModel;
use Models\TagModel;
use Models\GenerateColumn;
use Services\ManagerFiltersMixin;

class AcompanimentService extends Service
{
    use GenerateColumn;

    public $stage = '';
    private $tables = [
        'themes' => ThemeModel::class,
        'stages' => StageModel::class,
        'topics' => TopicModel::class,
        'tags' => TagModel::class
    ];

    public $tableNamesTransleted = [
        'temas' => 'themes',
        'estagios' => 'stages',
        'topicos' => 'topics',
        'tags' => 'tags'
    ];

    function spellLabel($dataPossible)
    {
        [$labelName, $valueSearch] = explode(':', $dataPossible);

        if (!array_key_exists($labelName, $this->tableNamesTransleted)) {
            return null;
        }

        $tableName  = $this->tableNamesTransleted[$labelName];
        $modelClass = $this->tables[$tableName];

        return [
            'model' => $modelClass,
            'valueSearch' => $valueSearch
        ];
    }

    function filterforTable($dataPossible)
    { 
        $resultSpell = $this->spellLabel($dataPossible);

        if (!$resultSpell) {
            return null;
        }

        $modelClass = $resultSpell['model'];
        $valueSearch = $resultSpell['valueSearch'];
        $instanceModel = new $modelClass();
        $instanceModel->set('name', $valueSearch);
        $instanceModel->where('name');
        return $instanceModel;
    }

    function orderTasksForDate($order)
    { 
        if (!in_array($order, ['asc', 'desc'])) {
            return null;
        }

        $this->table->orderBy('updated_at', $order);
        return $this->table;
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
    function filterForExpiredTime($term='')
    {
        $date_current = date('Y-m-d H:i:s');
        $this->table->set('end_date', $date_current);

        if ($term == 'expired') {
            return $this->table->where('end_date', '<');
        }

        return $this->table->where('end_date', '>');
    }

    /**
     * Filters records by status condition
     *
     * This method applies a WHERE clause to the table query based on the provided status parameter.
     * By default, it sets the status to -1 (future) and uses an equality operator.
     * If the provided status differs from the 'future' option (-1), it switches to a not-equal operator instead.
     *
     * @param int $status The status value to filter by. Use -1 for future status, or any other value for non-future status.
     *
     * @return object Returns the table object with the WHERE clause applied, allowing for method chaining.
     */
    function statusFilter($status)
    {
        $statusOptions = ['started' => 0, 'future' => -1];
        $this->table->set('status', -1);
        $operator = $this->table::OPERADORES['EQ'];

        if ($statusOptions[$status] != $statusOptions['future']) {
            $operator = $this->table::OPERADORES['NEQ'];
        }
        
        return $this->table->where('status', $operator);
    }

    function amountContentOfStudyFilter(bool $isLot)
    {
        $this->table->set('lot_to_discuss', $isLot);
        return $this->table->where('lot_to_discuss', $this->table::OPERADORES['EQ']);

    }

    function managerFilters(ManagerFiltersMixin $dataFilters){
        $table_name_with_search = '';
        $order                  = 'asc';
        $term                   = 'expired';
        $isLot                  = true;

        // $filtersOptions = [
        //     'filterforTable' => 'table_name_with_search',
        //     'OrderTasksForDate' => 'order',
        //     'filterForExpiredTime' => 'term',
        //     'statusFilter' => 'status',
        //     'amountContentOfStudyFilter' => 'isLot'
        // ];

        $this->filterforTable    ($table_name_with_search);  //////Theme,Topic,Tag
        $this->orderTasksForDate($order);                    //StageModel
        $this->statusFilter     ('started');                 //StageModel
        $this->filterForExpiredTime($term); //TopicModel
        $this->amountContentOfStudyFilter($isLot); //TopicModel
    }
}
