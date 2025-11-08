<?php

namespace Database\Migrations;

use Database\DB;

class CreateTableRevision
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
            CREATE TABLE IF NOT EXISTS revisions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                dominated BOOLEAN DEFAULT FALSE,
                status iNT DEFAULT -1,
                topic_id INT NOT NULL,
                description TEXT,
                observations TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'revisions' criada com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao criar tabela 'revisions': " . $e->getMessage();
        }
    }
    public function down()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "DROP TABLE IF EXISTS revisions;";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'revisions' removida com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao remover tabela 'revisions': " . $e->getMessage();
        }
    }
}
