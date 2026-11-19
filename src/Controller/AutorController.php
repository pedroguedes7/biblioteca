<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Form\AutorType;
use App\Repository\AutorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/autores', name: 'app_autor_')]
final class AutorController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(AutorRepository $autorRepository): Response
    {
        return $this->render('autor/index.html.twig', [
            'autores' => $autorRepository->findBy([], ['nome' => 'ASC']),
        ]);
    }

    #[Route('/novo', name: 'novo', methods: ['GET', 'POST'])]
    public function novo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $autor = new Autor();
        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($autor);
            $entityManager->flush();

            $this->addFlash('success', 'Autor cadastrado com sucesso.');

            return $this->redirectToRoute('app_autor_index');
        }

        return $this->render('autor/form.html.twig', [
            'form' => $form,
            'titulo' => 'Cadastrar Autor',
        ]);
    }

    #[Route('/{id}/editar', name: 'editar', methods: ['GET', 'POST'])]
    public function editar(Request $request, EntityManagerInterface $entityManager, Autor $autor): Response
    {
        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Autor atualizado com sucesso.');

            return $this->redirectToRoute('app_autor_index');
        }

        return $this->render('autor/form.html.twig', [
            'form' => $form,
            'titulo' => 'Editar Autor',
        ]);
    }

    #[Route('/{id}/excluir', name: 'excluir', methods: ['POST'])]
    public function excluir (Autor $autor, Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response {
        $token = $request->request->get('_token');

        if (!$this->isCsrfTokenValid('delete' . $autor->getCodAu(), $token)) {
            $this->addFlash('danger', 'Token de segurança inválido.');

            return $this->redirectToRoute('app_autor_index');
        }

        if (!$autor->getLivros()->isEmpty()) {
            $this->addFlash(
                'warning',
                'Não é possível excluir um autor que possui livros associados.'
            );

            return $this->redirectToRoute('app_autor_index');
        }

        try {
            $entityManager->remove($autor);
            $entityManager->flush();

            $this->addFlash('success', 'Autor excluído com sucesso!');
        } catch (ForeignKeyConstraintViolationException $exception) {
            $logger->warning(
                'Não foi possível excluir o autor devido a uma restrição de chave estrangeira.',
                [
                    'autor_id' => $autor->getCodAu(),
                    'exception' => $exception,
                ]
            );

            $this->addFlash(
                'danger',
                'Não foi possível excluir o autor porque existem registros vinculados.'
            );
        }

        return $this->redirectToRoute('app_autor_index');
    }
}
