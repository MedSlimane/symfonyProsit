<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,

        ]);
    }
    #[Route('/book/Add', name: 'book_new')]
    public function new(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $book = new Book();
        $book->setPublished(true);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $e = $managerRegistry->getManager();
            $e->persist($book);
            $e->flush();

            return $this->redirectToRoute('book_success');
        }

        return $this->render('book/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/book/success', name: 'book_success')]
    public function successAdd() : Response
    {
        return $this->render('book/success.html.twig');
    }
    #[Route('/book/edit/{id}', name: 'book_edit')]
    public function edit(Request $request, Book $book, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(BookType::class, $book, [
            'is_edit' => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $e = $managerRegistry->getManager();
            $e->persist($book);
            $e->flush();

            return $this->redirectToRoute('app_book');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/book/delete/{id}', name: 'book_delete')]
    public function delete(Request $request, Book $book, ManagerRegistry $managerRegistry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $e = $managerRegistry->getManager();
            $e->remove($book);
            $e->flush();
        }

        return $this->redirectToRoute('app_book');
    }
}
