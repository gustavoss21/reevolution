<?php

namespace Database\Migrations;

use Database\DB;

class InsertColumnsInStage
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
            ALTER TABLE stages
                ADD summary TEXT,
                ADD synthesis VARCHAR(255),
                ADD status TINYINT(1) CHECK (status IN (1, 2, 3)),
                ADD domain_level INT NOT NULL,
                ADD attention text,
                ADD learning_stage int NOT NULL,
                ADD priority int NOT NULL
            ;
        ";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'stage' compos summary, synthesis, status, attention, learning_stage domain adicionados com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao criar compos summary, synthesis, status, domain, attention, learning_stage na tabela 'stage': " . $e->getMessage();
        }
    }
    public function down()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "ALTER TABLE stages 
                DROP COLUMN summary,
                DROP COLUMN synthesis,
                DROP COLUMN status,
                DROP COLUMN domain_level,
                DROP COLUMN attention,
                DROP COLUMN priority,
                DROP COLUMN learning_stage;";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'stages', campos summary, synthesis, domain, attention, learning_stage status removidos com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao remover compos summary, synthesis, domain, attention, learning_stage status da tabela 'stages': " . $e->getMessage();
        }
    }
}
