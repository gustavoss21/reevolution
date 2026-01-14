<?php

namespace Test;

require dirname(__FILE__, 2) . '/vendor/autoload.php';

use Dotenv\Util\Regex;
use Services\ConsultService;

class ConsultTest{
    function getFormTest(){
        $insC = new ConsultService();
        return $insC->getFormRecursive('tags');
    }
}

$instanceTest = new ConsultTest();
print_r($instanceTest->getFormTest());
