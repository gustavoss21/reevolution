<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Validação das variáveis de ambiente
$required = ['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
foreach ($required as $var) {
    if (empty($_ENV[$var])) {
        die("❌ Variável de ambiente '$var' não definida.");
    }
}

return conectarBanco();

function conectarBanco()
{
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $db = $_ENV['DB_DATABASE'];
    $user = $_ENV['DB_USERNAME'];
    $pass = $_ENV['DB_PASSWORD'];

    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "✅ Conexão bem-sucedida!";
        return $pdo;
    } catch (PDOException $e) {
        // Em produção, logue o erro ao invés de exibir
        // echo "❌ Erro na conexão.";
        error_log($e->getMessage());
        return null;
    }
}
