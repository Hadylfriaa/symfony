<?php

namespace App\Controller;
use App\Repository\BookRepository;
use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/showbook', name: 'app_showbook')]
    public function showbook(BookRepository $bookrepo): Response
    {
        $y = $bookrepo->findAll();
        return $this->render('book/show.html.twig', [
            'books'=> $y
        ]);
    }
    #[Route('/addbook', name: 'addbook')]
    public function addbook(ManagerRegistry $manager, Request $req): Response

    {
        $em = $manager->getManager();
        $book = new Book();
        $form = $this->createForm(BookType::class,  $book);
        $form->HandleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('showDBbook');
        }
        return $this->renderForm('book/addbook.html.twig', [
            'book' => $form,
        ]);
    }
    #[Route('/showDBbook', name: 'showDBbook')]
    public function showDBbook(BookRepository $repo): Response
    {
        $books = $repo->findAll();

        return $this->render('book/showDBbook.html.twig', [
            'books' => $books,
        ]);
    }
    #[Route('/listebook', name: 'listebook')]

    public function listebook(Request $request, BookRepository $bookRepository): Response
    {
        $ref = $request->query->get('ref');
        $book = [];

        if ($ref) {
            $book = $bookRepository->findByRef($ref);
        } else {
            $book = $bookRepository->findAll();
        }

        return $this->render('book/listebook.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/bookbyAuthor', name: 'bookbyAuthor')]
    public function bookbyAuthor(BookRepository $BookRepository): Response
    {
        $book = $BookRepository->findByAuteur();

        return $this->render('book/bookbyAuthor.html.twig', [
            'book' => $books,
        ]);
    }

    #[Route('/booksBefore2023MoreThan35', name: 'booksBefore2023MoreThan35')]
    public function booksBefore2023MoreThan35(BookRepository $bookRepository): Response
    {
        $book = $bookRepository->findBooksBefore2023WithAuthorMoreThan35Books();

        return $this->render('book/booksBefore2023MoreThan35.html.twig', [
            'book' => $books,
        ]);
    }
}
