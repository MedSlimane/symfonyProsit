<?php

namespace App\Controller;

use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(AuthorRepository $authorRepository, BookRepository $bookRepository): Response
    {
        $authors = $authorRepository->findAll();
        $numberOfPublishedBooks = $bookRepository->count(['published' => true]);
        $numberOfUnPublishedBooks = $bookRepository->count(['published' => false]);
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
            'nb_published' => $numberOfPublishedBooks,
            'nb_unpublished' => $numberOfUnPublishedBooks
        ]);
    }
    #[Route('/author/Add', name: 'author_new')]
    public function new(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $e = $managerRegistry->getManager();
            $e->persist($author);
            $e->flush();

            return $this->redirectToRoute('app_success');
        }

        return $this->render('author/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/author/success', name: 'app_success')]
    public function successAdd() : Response
    {
        return $this->render('author/success.html.twig');
    }
    #[Route('/author/edit/{id}', name: 'author_edit')]
    public function edit(Request $request, Author $author, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $e = $managerRegistry->getManager();
            $e->persist($author);
            $e->flush();

            return $this->redirectToRoute('app_author');
        }

        return $this->render('author/edit.html.twig',[
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }
    #[Route('/author/delete/{id}', name: 'author_delete')]
    public function delete(Request $request, Author $author, ManagerRegistry $managerRegistry): Response
    {
        if ($this->isCsrfTokenValid('delete' . $author->getId(), $request->request->get('_token')))
        {
            $e = $managerRegistry->getManager();
            $e->remove($author);
            $e->flush();
        }
        return $this->redirectToRoute('app_author');
    }
    #[Route('/author/minmax',name: 'authorMinMax')]
    public function minMax(Request $request, AuthorRepository $authorRepository, BookRepository $bookRepository) : Response
    {
        $min = $request->get('min');
        $max = $request->get('max');

        $authors = $authorRepository->minMaxnbBooks($min,$max);

        $numberOfPublishedBooks = $bookRepository->count(['published' => true]);
        $numberOfUnPublishedBooks = $bookRepository->count(['published' => false]);
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
            'nb_published' => $numberOfPublishedBooks,
            'nb_unpublished' => $numberOfUnPublishedBooks
        ]);

    }

    #[Route('/author/deletezero', name : 'deleteZeroBooks')]
    public function deleteZeroBooks(AuthorRepository $authorRepository) : Response {

        $authorRepository->deleteZeroBooks();

        return $this->redirectToRoute('app_author');
    }

    #[Route('/author/listbyemail', name : 'listEmail')]
    public function listEmail(AuthorRepository $authorRepository, BookRepository $bookRepository) : Response {

        $authors = $authorRepository->listAuthorsByEmail();

        $numberOfPublishedBooks = $bookRepository->count(['published' => true]);
        $numberOfUnPublishedBooks = $bookRepository->count(['published' => false]);        

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
            'nb_published' => $numberOfPublishedBooks,
            'nb_unpublished' => $numberOfUnPublishedBooks
        ]);
    }
    
}
