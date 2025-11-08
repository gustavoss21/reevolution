<?php

namespace Database\Migrations;

use Database\DB;

class CreateTableRelationshipTagsTopics
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
            CREATE TABLE IF NOT EXISTS relationship_tags_topics (
                id INT AUTO_INCREMENT PRIMARY KEY,
                tag_id int NOT NULL,
                topic_id int NOT NULL,
                FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
                FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE   
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'relationship_tags_topics' criada com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao criar tabela 'relationship_tags_topics': " . $e->getMessage();
        }
    }
    public function down()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "DROP TABLE IF EXISTS relationship_tags_topics;";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'relationship_tags_topics' removida com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao remover tabela 'relationship_tags_topics': " . $e->getMessage();
        }
    }
}
