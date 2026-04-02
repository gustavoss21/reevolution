<?php

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
        $this->insC = new ConsultService();
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
        $newServiceForTheme = new ConsultService('Models\Theme');
        print_r($newServiceForTheme);
        $result = $newServiceForTheme->searchForOther('TopicModel', 'theme_id')->find();
        $this->assertContainsOnlyArray($result);
    }
}

// $instanceTest = new ConsultTest();
// print_r($instanceTest->getFormTest());
