<?php

namespace Database\Migrations;

use Database\DB;

class CreateTableFonts
{
    public function teste(){
        echo "Teste de migração";
    }

    public function up()
    {
        $pdo = DB::conectarBanco();

        if (!$pdo) {
            return;
        }

        $sql = "
            CREATE TABLE IF NOT EXISTS fonts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                font VARCHAR(255) NOT NULL,
                description TEXT,
                stage_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (stage_id) REFERENCES stages(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'fonts' criada com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao criar tabela 'fonts': " . $e->getMessage();
        }
    }
    public function down()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "DROP TABLE IF EXISTS fonts;";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'fonts' removida com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao remover tabela 'fonts': " . $e->getMessage();
        }
    }
}