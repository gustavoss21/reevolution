<?php

namespace Database;

require dirname(__FILE__, 2) . '/vendor/autoload.php';

use Database\MigrationMixins;

$path     = dirname(__DIR__) . '/database/migrations/';
$data_set = array_slice($argv, 1);

foreach($data_set as $arg){
    if(in_array($arg, ['up', 'down'])){
        $migrationMetode = $arg;
       
        continue;
    }
    $migrationNames[]  = $arg;
}
// $migrationNames  = $argv[1] ? [$argv[1]] : null;                              // obtem o nome da migração a ser executada
$migrationMetode = isset($migrationMetode) ? $migrationMetode : 'up';  // verifica se o metodo é down
$listmigrations  = scandir($path);                                            // obtem a lista de arquivos no diretório de migrações
// Funçãolistmigrations para extrair o nome da classe a partir do arquivo
$listmigrations = array_filter($listmigrations, function ($file) {
    return preg_match('/\.php$/', $file); // filtra apenas arquivos PHP
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

MigrationMixins::exeMigration($optionFormigration, $migrationMetode);