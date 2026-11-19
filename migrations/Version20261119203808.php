<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261119203808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

   public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE VIEW vw_livros_por_autor AS
            SELECT
                a.codau AS autor_id,
                a.nome AS autor_nome,
                l.codl AS livro_id,
                l.titulo,
                l.editora,
                l.edicao,
                l.ano_publicacao AS ano_publicacao,
                l.valor
            FROM autor a
            INNER JOIN livro_autor la
                ON la.autor_codau = a.codau
            INNER JOIN livro l
                ON l.codl = la.livro_codl
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW IF EXISTS vw_livros_por_autor');
    }
}
