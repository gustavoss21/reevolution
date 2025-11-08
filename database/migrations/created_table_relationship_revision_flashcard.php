<?php

namespace Database\Migrations;

use Database\DB;

class CreateTableRelationshipRevisionFlashcard
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
            CREATE TABLE IF NOT EXISTS relationship_revisions_flashcards (
                id INT AUTO_INCREMENT PRIMARY KEY,
                revision_id INT NOT NULL,
                flashcard_id INT NOT NULL,
                FOREIGN KEY (revision_id) REFERENCES revisions(id) ON DELETE CASCADE,
                FOREIGN KEY (flashcard_id) REFERENCES flashcards(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'relationship_revisions_flashcards' criada com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao criar tabela 'relationship_revisions_flashcards': " . $e->getMessage();
        }
    }
    public function down()
    {
        $pdo = DB::conectarBanco();
        if (!$pdo) {
            return;
        }

        $sql = "DROP TABLE IF EXISTS relationship_revisions_flashcards;";

        try {
            $pdo->exec($sql);
            echo "✅ Tabela 'relationship_revisions_flashcards' removida com sucesso!";
        } catch (\PDOException $e) {
            echo "❌ Erro ao remover tabela 'relationship_revisions_flashcards': " . $e->getMessage();
        }
    }
}
