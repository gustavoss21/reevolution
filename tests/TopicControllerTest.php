<?php

namespace Test;

require dirname(__DIR__) . '/vendor/autoload.php';

use Controllers\TopicController;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;


use Dotenv\Util\Regex;
use Services\ConsultService;

class TopicControllerTest extends TestCase
{
    function testgetTopicforTheme(){
        $topic = new TopicController();
        $data = $topic->getTopicforTheme(['id' => 2]);
        print_r($data);
        $this->assertIsArray($data, 'deve ser um array');

        // Opcional: Verifica se o array tem chaves esperadas (ex.: 'id')
        $this->assertArrayHasKey('id', $data, 'O array deve conter a chave "id"');
    }
 
}
