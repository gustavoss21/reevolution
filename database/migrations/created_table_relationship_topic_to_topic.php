<?php

namespace Database\Migrations;

use Database\DB;

class CreateTableRelationshipTopicToTopic
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
            CREATE TABLE IF NOT EXISTS relationship_topic_to_topic (
                id INT AUTO_INCREMENT PRIMARY KEY,
                topic_father_id INT NOT NULL,
                topic_child_id INT NOT NULL,
                type_relationship VARCHAR(100) DEFAULT 'dependency',
                FOREIGN KEY (topic_father_id) REFERENCES topics(id) ON DELETE CASCADE,
                FOREIGN KEY (topic_child_id) REFERENCES topics(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'relationship_topic_to_topic' criada com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao criar tabela 'relationship_topic_to_topic': " . $e->getMessage();
        }
    }
    public function down()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "DROP TABLE IF EXISTS relationship_topic_to_topic;";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'relationship_topic_to_topic' removida com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao remover tabela 'relationship_topic_to_topic': " . $e->getMessage();
        }
    }
}
