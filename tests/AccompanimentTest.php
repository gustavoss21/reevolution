<?php

declare(strict_types=1);

namespace Test;

require dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;


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
             $result, 'spellModel: O resultado deve conter a chave "model"'
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
}

// $instanceTest = new ConsultTest();
// print_r($instanceTest->testsearchForOther());

