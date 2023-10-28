<?php

namespace App\Controller;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;


class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    
    #[Route('/showauthor', name: 'showauthor')]
    public function showauthor(AuthorRepository $authorRepo): Response
    {

        $x = $authorRepo->findAll();
        return $this->render('author/showauthor.html.twig', [
            'authors' => $x
        ]);
    }

    #[Route('/showDBauthor', name: 'showDBauthor')]
    public function showDBauthor(AuthorRepository $AuthorRepository, BookRepository $bookRepository): Response
    {
        $authors = $AuthorRepository->findAll();
        $authorBookCounts = $bookRepository->findBookCountsByAuthor();

        return $this->render('author/showDBauthor.html.twig', [
            'authors' => $authors,
            'authorBookCounts' => $authorBookCounts,

        ]);
    }
    
    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $manager, Request $req): Response
    {
        $em = $manager->getManager();
        $author = new Author();
        $form = $this->createForm(AuthorType::class,   $author);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('showDBauthor');
        }

        return $this->renderForm('author/add.html.twig', [
            'add' => $form
        ]);
    }
  
    #[Route('/editAuthor/{id}', name: 'editAuthor')]
    public function editAuthor($id, ManagerRegistry $manager, AuthorRepository $repo, Request $req): Response

    {
        $em = $manager->getManager();
        $idData = $repo->find($id);
        $form = $this->createForm(AuthorType::class, $idData);
        $form->HandleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($idData);
            $em->flush();
            return $this->redirectToRoute('showDBauthor');
        }

        return $this->renderForm('author/edit.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteauthor($id, ManagerRegistry $manager, AuthorRepository $repo): Response
    {
        $emm = $manager->getManager();
        $idremove = $repo->find($id);
        $emm->remove($idremove);
        $emm->flush();


        return $this->redirectToRoute('showDBauthor');
    }
    #[Route('/showbyemail', name: 'showbyemail')]
    public function showbyemail(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findByEmail();
        //var_dump($authors);
        return $this->render('author/showbyemail.html.twig', [
            'authors' => $authors,
        ]);
    }
}
