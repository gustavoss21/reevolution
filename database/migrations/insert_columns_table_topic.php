<?php

namespace Database\Migrations;

use Database\DB;

class insertColumnsTableTopic
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
            ALTER TABLE topics
                ADD COLUMN actived BOOLEAN DEFAULT TRUE,
                ADD COLUMN can_explain BOOLEAN,
                ADD COLUMN study_time int,
                ADD COLUMN times_week_necessary BOOLEAN,
                ADD COLUMN domain_week BOOLEAN,
                ADD COLUMN started_study BOOLEAN,
                ADD COLUMN lot_to_discuss BOOLEAN,
                ADD COLUMN end_date TIMESTAMP
                

        ";

        try {
            $pdo->exec($sql);
            echo "✅ coluna 'topics.columns' adicionada com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao adicionar coluna 'topics.columns': " . $e->getMessage();
        }
    }
    public function down()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "ALTER TABLE topics 
                DROP COLUMN actived,
                DROP COLUMN can_explain,
                DROP COLUMN study_time,
                DROP COLUMN times_week_necessary,
                DROP COLUMN domain_week,
                DROP COLUMN started_study,
                DROP COLUMN lot_to_discuss,
                DROP COLUMN end_date
                ;";


        try {
            $pdo->exec($sql);
            echo "✅ Coluna 'topics.columns' removida com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao remover coluna 'topics.columns': " . $e->getMessage();
        }
    }
}
