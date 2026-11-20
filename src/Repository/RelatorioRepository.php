<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class RelatorioRepository
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function buscarLivrosPorAutor(): array
    {
        return $this->connection->fetchAllAssociative('
            SELECT
                autor_id,
                autor_nome,
                livro_id,
                titulo,
                editora,
                edicao,
                ano_publicacao,
                valor
            FROM vw_livros_por_autor
            ORDER BY autor_nome, titulo
        ');
    }
}
