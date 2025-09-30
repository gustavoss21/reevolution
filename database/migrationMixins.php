<?php

namespace Database;

class MigrationMixins{

    // Executa as migrações no banco de dados
    static function exeMigration($configMigration, $migrationMetode = 'up')
    {
        $namespace = 'Database\Migrations\\';

        foreach ($configMigration as $migration) {
            $filePath = dirname(__DIR__) . '/database/migrations/' . $migration['filename'];
            require_once $filePath;
            $migrationInstance = new ($namespace . $migration['classname'])();
            $migrationInstance->{$migrationMetode}();
        }
    }

    // Verifica se a classe existe no namespace
    static function classExistsInNamespace($className)
    {
        return class_exists($className);
    }

    // obtem o nome da classe a partir do arquivo
    static function getClassNameFromFile($filePath)
    {

        $contents = file_get_contents($filePath);
        if (preg_match('/class\s+([^\s{]+)/', $contents, $matches)) {
            return ['filename' => basename($filePath), 'classname' => trim($matches[1])];
        }
        return null;
    }

    // obtem o path do arquivo a partir do nome da classe
    static function getFilePathFromClassName($filePath, $className)
    {
        $pathern = '/class\s+' . preg_quote($className) . '/';
        $contents = file_get_contents($filePath);
        if (preg_match($pathern, $contents, $matches)) {
            return trim($matches[1]);
        }
    }
}
