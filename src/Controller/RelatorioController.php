<?php

namespace App\Controller;

use App\Repository\RelatorioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RelatorioController extends AbstractController
{
    #[Route('/relatorios/livros-por-autor', name: 'app_relatorio_livros_por_autor', methods: ['GET'])]
    public function livrosPorAutor( RelatorioRepository $relatorioRepository): Response
    {
        $livros = $relatorioRepository->buscarLivrosPorAutor();

        $livrosPorAutor = [];

        foreach ($livros as $livro) {
            $autorId = $livro['autor_id'];

            if (!isset($livrosPorAutor[$autorId])) {
                $livrosPorAutor[$autorId] = [
                    'nome' => $livro['autor_nome'],
                    'livros' => [],
                ];
            }

            $livrosPorAutor[$autorId]['livros'][] = $livro;
        }

        return $this->render('relatorio/livros_por_autor.html.twig', [
            'autores' => $livrosPorAutor,
        ]);
    }
}
