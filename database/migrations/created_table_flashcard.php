<?php

namespace Database\Migrations;

use Database\DB;

class CreateTableFlashcard
{
    public function teste()
    {
        echo "Teste de migração";
    }

    public function up()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "
            CREATE TABLE IF NOT EXISTS flashcards (
                id INT AUTO_INCREMENT PRIMARY KEY,
                card VARCHAR(255) NOT NULL,
                description TEXT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'flashcards' criada com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao criar tabela 'flashcards': " . $e->getMessage();
        }
    }
    public function down()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "DROP TABLE IF EXISTS flashcards;";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'flashcards' removida com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao remover tabela 'flashcards': " . $e->getMessage();
        }
    }
}
