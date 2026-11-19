<?php

namespace App\Controller;

use App\Entity\Assunto;
use App\Form\AssuntoType;
use App\Repository\AssuntoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/assuntos', name: 'app_assunto_')]
final class AssuntoController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(AssuntoRepository $assuntoRepository): Response
    {
        return $this->render('assunto/index.html.twig', [
            'assuntos' => $assuntoRepository->findBy([], ['descricao' => 'ASC']),
        ]);
    }

    #[Route('/novo', name: 'novo', methods: ['GET', 'POST'])]
    public function novo(Request $request, EntityManagerInterface $entityManager): Response {
        $assunto = new Assunto();

        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($assunto);
            $entityManager->flush();

            $this->addFlash('success', 'Assunto cadastrado com sucesso.');

            return $this->redirectToRoute('app_assunto_index');
        }

        return $this->render('assunto/form.html.twig', [
            'form' => $form,
            'titulo' => 'Cadastrar Assunto',
        ]);
    }

    #[Route('/{id}/editar', name: 'editar', methods: ['GET', 'POST'])]
    public function editar(Assunto $assunto, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Assunto atualizado com sucesso.');

            return $this->redirectToRoute('app_assunto_index');
        }

        return $this->render('assunto/form.html.twig', [
            'form' => $form,
            'titulo' => 'Editar Assunto',
        ]);
    }

    #[Route('/{id}/excluir', name: 'excluir', methods: ['POST'])]
    public function excluir(Assunto $assunto, Request $request, EntityManagerInterface $entityManager): Response {

        if (!$this->isCsrfTokenValid('delete' . $assunto->getCodAs(),$request->request->get('_token'))){
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        if (!$assunto->getLivros()->isEmpty()) {
            $this->addFlash(
                'warning',
                'Não é possível excluir este assunto porque existem livros associados a ele.'
            );

            return $this->redirectToRoute('app_assunto_index');
        }

        $entityManager->remove($assunto);
        $entityManager->flush();

        $this->addFlash('success', 'Assunto excluído com sucesso.');

        return $this->redirectToRoute('app_assunto_index');
    }
}
