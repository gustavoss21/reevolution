<?php

declare(strict_types=1);

namespace Test;

require dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;


use Services\ConsultService;


class ConsultTest extends TestCase
{
    public $insC;

    protected function setUp(): void
    {
        parent::setUp();
        $this->insC = new ConsultService('Models\ThemeModel');
    }

    public function testGetForm()
    {

        $data = $this->insC->getFormRecursive('tag');
        $this->assertIsArray($data, 'testGetForm: Não é um array');
    }

    function dataTest()
    {
        // $this->assertIsArray();qq
    }

    function testsearchForOther()
    {
        // xdebug_break();
        $newServiceForTheme = new ConsultService('Models\TopicModel');
        $result             = $newServiceForTheme->searchForOther('Theme', 1)->find();
        print_r($result);
        $this->assertContainsOnlyArray($result, 'testsearchForOther: O resultado não é um array');
    }

    function testTimeline()
    {
        // xdebug_break();
        $stage_service = new ConsultService('Models\StageModel');
        $result        = $stage_service->paginate(0,5)->timeline();
        echo '------------------RESULTADO------------------';
        echo '<pre>';
        // print_r($result);
        echo '</pre>';
        $this->assertEquals(5, count($result), 'timeline: O resultado deve conter 5 itens');
        $this->assertContainsOnlyArray($result, 'timeline: O resultado não é um array');
    }
}

// $instanceTest = new ConsultTest();
// print_r($instanceTest->testsearchForOther());

