<?php

namespace App\Controller;

use App\Entity\Livro;
use App\Form\LivroType;
use App\Repository\LivroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livros', name: 'app_livro_')]
class LivroController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(LivroRepository $livroRepository): Response
    {
        $livros = $livroRepository->findBy([],['titulo' => 'ASC']);

        return $this->render('livro/index.html.twig', [
            'livros' => $livros,
        ]);
    }

    #[Route('/novo', name: 'novo', methods: ['GET', 'POST'])]
    public function novo(Request $request, EntityManagerInterface $entityManager): Response {
        $livro = new Livro();

        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livro);
            $entityManager->flush();

            $this->addFlash('success', 'Livro cadastrado com sucesso!');

            return $this->redirectToRoute('app_livro_index');
        }

        return $this->render('livro/form.html.twig', [
            'form' => $form,
            'titulo' => 'Cadastrar Livro',
        ]);
    }

    #[Route('/{id}/editar', name: 'editar', methods: ['GET', 'POST'])]
    public function editar(#[MapEntity(id: 'id')] Livro $livro, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Livro atualizado com sucesso!');

            return $this->redirectToRoute('app_livro_index');
        }

        return $this->render('livro/form.html.twig', [
            'form' => $form,
            'titulo' => 'Editar Livro',
        ]);
    }

    #[Route('/{id}/excluir', name: 'excluir', methods: ['POST'])]
    public function excluir(#[MapEntity(id: 'id')] Livro $livro, Request $request, EntityManagerInterface $entityManager): Response {
        $token = $request->request->get('_token');

        if (!$this->isCsrfTokenValid(
            'delete' . $livro->getCodL(),
            $token
        )) {
            $this->addFlash('danger', 'Token de segurança inválido.');

            return $this->redirectToRoute('app_livro_index');
        }

        $entityManager->remove($livro);
        $entityManager->flush();

        $this->addFlash('success', 'Livro excluído com sucesso!');

        return $this->redirectToRoute('app_livro_index');
    }
}
