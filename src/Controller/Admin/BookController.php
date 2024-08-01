<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'admin_book')]
    public function index(BookRepository $repository): Response
    {
        $books = $repository->findAll();
        //创建一个新的数组,用于存储书记和作者
        $booksWithAuthors = [];
        foreach ($books as $book){
            $authors = $book->getAuthors();
            $authorNames = [];

            foreach ($authors as $author) {
                $authorNames[] = $author->getName();
            }

            $booksWithAuthors[] = [
                'book' => $book,
                'authors' => implode(', ', $authorNames)
            ];
        }
        
        return $this->render('admin/book/index.html.twig', [
            'controller_name' => 'BookController',
            'books_with_authors' => $booksWithAuthors
        ]);
        
    }

    //Ajouter un nouveau livre
    #[Route('/new', name: 'admin_book_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('admin_book');
        
        }

        return $this->render('admin/book/new.html.twig', [
            'form' => $form,
        ]);
    }

    //show
    #[Route('/{id}', name: 'admin_book_show', methods:['GET'])]
    public function show(?Book $book): Response
    {
        return $this->render('admin/book/show.html.twig', [
            'book' => $book
        ]);
    }
}
