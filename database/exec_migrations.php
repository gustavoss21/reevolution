<?php

namespace Database;

require dirname(__FILE__, 2) . '/vendor/autoload.php';

use Database\MigrationMixins;

$path = dirname(__DIR__) . '/database/migrations/';

$migrationNames  = $argv[1] ? [$argv[1]] : null;                                // obtem o nome da migração a ser executada
$migrationMetode = !is_null($argv[2]) && $argv[2] == 'down' ? 'down' : 'up';  // verifica se o metodo é down
$listmigrations  = scandir($path);                                            // obtem a lista de arquivos no diretório de migrações

// Função para extrair o nome da classe a partir do arquivo
$listmigrations = array_filter($listmigrations, function ($file) {
    return preg_match('/\.php$/', $file);  // filtra apenas arquivos PHP
});

$optionFormigration = array_map(function ($file) use ($path) {
    $className = MigrationMixins::getClassNameFromFile($path . $file);
    return $className;
}, $listmigrations); // obtem o nome da classe a partir do arquivo

// filtra a lista de migrações para encontrar a migração especificada
if (!empty($migrationNames)) {
    $optionFormigration = array_filter(
        $optionFormigration,
        function ($file) use ($migrationNames) {
            if ($file['classname'] == $migrationNames[0]) {
                return $file;
            }
        }
    );
}

  // print_r($optionFormigration);
  // $p = MigrationMixins::orderMigration($optionFormigration);
  // print_r($p);
  // return;

MigrationMixins::exeMigration($optionFormigration, $migrationMetode);
