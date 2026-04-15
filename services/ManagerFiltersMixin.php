<?php

declare(strict_types=1);

namespace Services;


readonly abstract class ManagerFiltersMixin{
    

    public function __construct(
        public ?string $filterforTable,
        public ?string $OrderTasksForDate,
        public ?string $filterForExpiredTime,
        public ?string $statusFilter,
        public ?string $amountContentOfStudyFilter
    ){
        
    }

    /**
     * Cria a instância a partir de um array associativo
     */
    public static function fromArray(array $data): self {
        return new self(
            $data['filterforTable'] ?? null,
            $data['OrderTasksForDate'] ?? null,
            $data['filterForExpiredTime'] ?? null,
            $data['statusFilter'] ?? null,
            $data['amountContentOfStudyFilter'] ?? null
        );
    }

    abstract function filterforTable($table_name_with_search);
    abstract function orderTasksForDate($order);
    abstract function filterForExpiredTime($term);
    abstract function statusFilter($status);
    abstract function amountContentOfStudyFilter($isLot);
}