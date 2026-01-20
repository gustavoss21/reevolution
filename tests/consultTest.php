<?php

namespace Test;

require dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;


use Dotenv\Util\Regex;
use Services\ConsultService;

class ConsultTest extends TestCase{
    function testGetForm(){
        $insC = new ConsultService();
        $data = $insC->getFormRecursive('tag');
        $this->assertIsArray($data, 'testGetForm: Não é um array');
    }

    function dataTest(){
        // $this->assertIsArray();qq
    }
}

// $instanceTest = new ConsultTest();
// print_r($instanceTest->getFormTest());
