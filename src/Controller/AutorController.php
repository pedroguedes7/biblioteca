<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Form\AutorType;
use App\Repository\AutorRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function excluir(Request $request, EntityManagerInterface $entityManager, Autor $autor): Response
    {
        if ($this->isCsrfTokenValid('delete' . $autor->getCodAu(), $request->request->get('_token'))) {

            if (!$autor->getLivros()->isEmpty()) {
                $this->addFlash(
                    'warning',
                    'Não é possível excluir este autor porque existem livros associados a ele.'
                );

                return $this->redirectToRoute('app_autor_index');
            }

            $entityManager->remove($autor);
            $entityManager->flush();

            $this->addFlash('success', 'Autor excluído com sucesso.');
        }

        return $this->redirectToRoute('app_autor_index');
    }
}
