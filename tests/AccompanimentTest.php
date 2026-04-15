<?php

declare(strict_types=1);

namespace Test;

require dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Models\ModelMixin;

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

    function testSpellModel()
    {
        $tables = [
            'ThemeModel',
            'StageModel',
            'TopicModel',
            'TagModel'
        ];

        $dataPossibleTrue = 'tags:facere';
        $dataPossibleFalse = 'tasfwe:nomeTeste';
        $result           = $this->insC->spellModel($dataPossibleTrue);

        $this->assertEquals(
            ['model' => 'Models\TagModel', 'valueSearch' => 'nomeTeste'],
            $result,
            'spellModel: O resultado deve conter a chave "model"'
        );

        $this->assertNull(
            $this->insC->spellModel($dataPossibleFalse),
            'spellModel: O resultado deve ser null para dados inválidos'
        );
    }

    function testFilterforTable()
    {
        $dataPossibleTrue  = 'tags:quia';

        $resultTrue = $this->insC->filterforTable($dataPossibleTrue);

        $this->assertInstanceOf(
            'Models\TagModel',
            $resultTrue,
            'filterforTable: O resultado deve ser uma instância de TagModel'
        );

        $data = $resultTrue->find();
        print_r($data);

        $this->assertIsArray(
            $data,
            'filterforTable: O resultado do método find deve ser um array'
        );
    }

    function testOrderTasksForDate()
    {
        $resultAsc      = (new AcompanimentService('Models\StageModel'))->OrderTasksForDate('asc');
        $resultDesc     = (new AcompanimentService('Models\StageModel'))->OrderTasksForDate('desc');
        $resultInvalid  = (new AcompanimentService('Models\StageModel'))->OrderTasksForDate('invalid');
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

    function testfilterForExpiredTime(){
        $intanceExpired  = (new AcompanimentService('Models\TopicModel'))->filterForExpiredTime('expired');
        $instanceCurrent = (new AcompanimentService('Models\TopicModel'))->filterForExpiredTime('current');
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

    function testStatusFilter(){
        $instanceStarted = (new AcompanimentService('Models\StageModel'))->statusFilter('started');
        $instanceFuture  = (new AcompanimentService('Models\StageModel'))->statusFilter('future');
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

    function testAmountContentOfStudyFilter(){
        $instanceLotYes = (new AcompanimentService('Models\TopicModel'))->amountContentOfStudyFilter(true);
        $instanceLotNo  = (new AcompanimentService('Models\TopicModel'))->amountContentOfStudyFilter(false);
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
}

// $instanceTest = new ConsultTest();
// print_r($instanceTest->testsearchForOther());
