<?php

namespace Database\Migrations;

use Database\DB;

class insertColumnActivedTableTopic
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
                ADD COLUMN actived BOOLEAN DEFAULT TRUE;
        ";

        try {
            $pdo->exec($sql);
            echo "✅ coluna 'topics.actived' adicionada com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao adicionar coluna 'topics.actived': " . $e->getMessage();
        }
    }
    public function down()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "ALTER TABLE topics 
                DROP COLUMN actived
                ;";
                

        try {
            $pdo->exec($sql);
            echo "✅ Coluna 'topics.actived' removida com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao remover coluna 'topics.actived': " . $e->getMessage();
        }
    }
}
