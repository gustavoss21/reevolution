<?php

declare(strict_types=1);

namespace Test;

require dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Models\ModelMixin;
use Models\TagModel;
use Models\ThemeModel;
use Models\TopicModel;
use Services\ManagerFilters;

use Services\AcompanimentService;
// use Models\ThemeModel;
// use Models\StageModel;
// use Models\TopicModel;
// use Models\TagModel;


class AccompanimentTest extends TestCase
{
    public $insC;

    protected function setUp(): void
    {
        parent::setUp();
        $this->insC = new AcompanimentService('Models\ThemeModel');
    }

    function testFromArray()
    {
        $data = [
            ['filterforTable' => 'tags:quia',],
            ['orderTasksForDate' => 'desc',],
            ['filterForExpiredTime' => 'expired',],
            ['statusFilter' => 'started',],
            ['amountContentOfStudyFilter' => true]
        ];

        $intance = new ManagerFilters();
        $intance->fromArray($data);
        print_r($intance);
        $this->assertInstanceOf(ManagerFilters::class, $intance, 'fromArray: O resultado deve ser uma instância de ManagerFilters');
        
    }

    function testSpellModel()
    {
        $tables = [
            'ThemeModel',
            'StageModel',
            'TopicModel',
            'TagModel'
        ];

        $dataPossibleTrue  = 'tags:facere';
        $dataPossibleFalse = 'tasfwe:nomeTeste';
        $result            = new ManagerFilters(['filterforTable' => $dataPossibleTrue]);
        $result            = $result->spellLabel($dataPossibleTrue);
        print_r($result);
        $this->assertEquals(
            ['modelName' => 'tags', 'valueSearch' => 'facere'],
            $result,
            'spellModel: O resultado deve conter a chave "model"'
        );

        // $this->assertNull(
        //     (new ManagerFilters(['filterforTable' => $dataPossibleFalse]))->spellModel($dataPossibleFalse),
        //     'spellModel: O resultado deve ser null para dados inválidos'
        // );
    }

    function testFilterforTable()
    {
        $dataPossibleTrue = 'quia';
        $table            = new TagModel();
        // $table            = new TagModel();
        // $table            = new TopicModel();
        $instance         = new ManagerFilters();
        $resultTrue       = $instance->filterforTable($dataPossibleTrue, $table);

        $this->assertInstanceOf(
            'Models\TagModel',
            $resultTrue,
            'filterforTable: O resultado deve ser uma instância de TagModel'
        );

        $data = $resultTrue->find();

        $this->assertIsArray(
            $data,
            'filterforTable: O resultado do método find deve ser um array'
        );
    }

    function testOrderTasksForDate()
    {
        $table = new ('Models\StageModel');
        $resultAsc      = (new ManagerFilters())->OrderTasksForDate('asc', $table);
        $resultDesc     = (new ManagerFilters())->OrderTasksForDate('desc', $table);
        $resultInvalid  = (new ManagerFilters())->OrderTasksForDate('invalid', $table);
        $resultDataAsc  = $resultAsc->find();
        $resultDataDesc = $resultDesc->find();
        print_r($resultDataAsc);


        $this->assertInstanceOf(
            ModelMixin::class,
            $resultDesc,
            'OrderTasksForDate: O resultado deve ser uma instância de AcompanimentService para ordem descendente'
        );

        $this->assertNull(
            $resultInvalid,
            'OrderTasksForDate: O resultado deve ser null para uma ordem inválida'
        );

        $this->assertIsArray(
            $resultDataAsc,
            'OrderTasksForDate: O resultado do método find deve ser um array'
        );

        $this->assertTrue($resultDataAsc[0]['updated_at'] <= $resultDataAsc[count($resultDataAsc) - 1]['updated_at'], 'OrderTasksForDate: Os dados devem estar ordenados em ordem ascendente');
        $this->assertTrue($resultDataDesc[0]['updated_at'] >= $resultDataDesc[count($resultDataDesc) - 1]['updated_at'], 'OrderTasksForDate: Os dados devem estar ordenados em ordem descendente');
    }

    function testfilterForExpiredTime()
    {
        $intanceExpired  = (new ManagerFilters('Models\TopicModel'))->filterForExpiredTime('expired', new ('Models\TopicModel'));
        $instanceCurrent = (new ManagerFilters('Models\TopicModel'))->filterForExpiredTime('current', new ('Models\TopicModel'));
        $resultExpired   = $intanceExpired->find();
        $resultCurrent   = $instanceCurrent->find();
        $date_current    = date('Y-m-d H:i:s');

        $this->assertTrue(
            count($resultExpired) == 0 || $resultExpired[0]['end_date'] < $date_current,
            'filterForExpiredTime: Os dados expirados devem ter end_date menor que a data atual'
        );

        $this->assertTrue(
            count($resultCurrent) == 0 || $resultCurrent[0]['end_date'] > $date_current,
            'filterForExpiredTime: Os dados atuais devem ter end_date maior que a data atual'
        );
    }

    function testStatusFilter()
    {
        $instanceStarted = (new ManagerFilters)->statusFilter('started', new ('Models\StageModel'));
        $instanceFuture  = (new ManagerFilters)->statusFilter('future', new ('Models\StageModel'));
        $resultStarted   = $instanceStarted->find();
        $resultFuture    = $instanceFuture->find();

        $this->assertTrue(
            !in_array(-1, array_column($resultStarted, 'status')),
            'statusFilter: Os dados com status "started" devem ter status igual a 0 ou 1'
        );

        $this->assertTrue(
            in_array(-1, array_column($resultFuture, 'status')),
            'statusFilter: Os dados com status "future" devem ter status igual a -1'
        );
    }

    function testAmountContentOfStudyFilter()
    {
        $instanceLotYes = (new ManagerFilters)->amountContentOfStudyFilter(true, new ('Models\TopicModel'));
        $instanceLotNo  = (new ManagerFilters)->amountContentOfStudyFilter(false, new ('Models\TopicModel'));
        $resultLotYes   = $instanceLotYes->find();
        $resultLotNo    = $instanceLotNo->find();

        $this->assertTrue(
            count($resultLotYes) == 0 || !in_array(0, array_column($resultLotYes, 'lot_to_discuss')),
            'amountContentOfStudyFilter: Os dados com lot_to_discuss "Sim" devem ter lot_to_discuss igual a 1'
        );

        $this->assertTrue(
            count($resultLotNo) == 0 || !in_array(1, array_column($resultLotNo, 'lot_to_discuss')),
            'amountContentOfStudyFilter: Os dados com lot_to_discuss "Não" devem ter lot_to_discuss igual a 0'
        );
    }

    function testManagerFilters()
    {
        $data = [
            // ['filterforTable' => 'tags:quia'],
            // ['filterforTable' => 'themes:Maia e Balestero e Filhos'],
            // ['filterforTable' => 'themes:Batista e Lourenço e Associados'],
            // ['orderTasksForDate' => 'desc'],
            // ['filterForExpiredTime' => 'expired'],
            // ['statusFilter' => 'started'],
            ['amountContentOfStudyFilter' => true]
        ];
        $instance     = new AcompanimentService();
        $searchResult = $instance->managerFilters($data);
        $this->assertIsArray(
            $searchResult,
            'managerFilters: O resultado deve ser um array'
        );
    }

    function testStepList(){
        $instance = new AcompanimentService();
        $list     = ['level1' => [['level3' => 'finalValue']]];

        $searchResult = &$instance->stepList(null, $list);
        debug_zval_dump($searchResult);

        $value2 = &$instance->stepList('level1');
        debug_zval_dump($value2);

        $value3        = &$instance->stepList(0);
        debug_zval_dump($value3);
        $this->assertIsArray(
            $searchResult,
            'stepList: O resultado deve ser um array'
        );
    }

    function testManagerFiltersOnlyThemes()
    {
        $data = [
            // ['filterforTable' => 'tags:quia'],
            // ['filterforTable' => 'themes:Maia e Balestero e Filhos'],
            // ['filterforTable' => 'themes:Batista e Lourenço e Associados'],
            // ['orderTasksForDate' => 'desc'],
            // ['filterForExpiredTime' => 'expired'],
            // ['statusFilter' => 'started'],
            ['amountContentOfStudyFilter' => true]
        ];
        $instance     = new AcompanimentService();
        $searchResult = $instance->managerFilters($data);
        print_r($searchResult);
        $this->assertIsArray(
            $searchResult,
            'managerFilters: O resultado deve ser um array'
        );
    }
}