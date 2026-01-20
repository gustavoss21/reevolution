<?php

namespace Test;

require dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;
use Config\RouterBase;
use Dotenv\Util\Regex;

use function PHPUnit\Framework\assertEquals;

class RouteTest extends TestCase
{
    function testRouteArgumentCompost()
    {
        $url = '127.0.0.1/reevolution/topics?id=2';
        $opt = curl_init();
        curl_setopt($opt, CURLOPT_URL, $url);
        curl_setopt($opt, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($opt);
        curl_close($opt);  // Boa prática: fechar a conexão

        // Verifica se é uma string
        $this->assertIsString($response, 'A resposta deve ser uma string');

        // Verifica se é JSON válido
        $this->assertJson($response, 'A resposta deve ser um JSON válido');

        // Decodifica e verifica se é um array
        $data = json_decode($response, true);
        $this->assertIsArray($data, 'O JSON decodificado deve ser um array');

        // Opcional: Verifica se o array tem chaves esperadas (ex.: 'id')
        $this->assertArrayHasKey('id', $data, 'O array deve conter a chave "id"');
    }

    function testFormatUri()
    {
        $route = new RouterBase();
        $uri          = "/reevolution/themes?id=5/topics";
        $body         = ['id' => "5/topics"];
        $uri_formated = $route->formatURI($uri, $body);
        print_r($uri_formated);
        static::assertEquals('/themes/{id}/topics', $uri_formated);
    }

    function testgetUriInQuerySearch(){
        $route       = new RouterBase();
        $body        = "/topics";
        // $body        = "5/topics";
        $body_result = $route->getUriInQuerySearch($body);
        print_r($body_result);
        static::assertIsArray($body_result, 'é um array');
    }
}
