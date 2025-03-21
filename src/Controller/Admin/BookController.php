<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\Entity\User;
use App\Form\BookType;  

use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use App\Repository\BookRepository;

#[Route("/admin/book")]
final class BookController extends AbstractController
{
    #[Route('/', name: 'app_admin_book')]
    public function index(BookRepository $repository, Request $request): Response
    {
        $books = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->createQueryBuilder('b')),
            $request->query->get('page', 1),
            2
        );
        return $this->render('admin/book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_book_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Book $book): Response
    {
        return $this->render('admin/book/show.html.twig', [
            'book' => $book,
        ]);
    }
    #[Route('/new', name: 'app_admin_book_new')]
    #[Route('/{id}/edit', name: 'app_admin_book_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(?Book $book, Request $request, EntityManagerInterface $em): Response
    {
        if($book){
            if(!$this->isGranted('ROLE_ADMIN')){
                $this->denyAccessUnlessGranted('book.is_creator', $book);
            }
        }
        if(null === $book){
            $this->denyAccessUnlessGranted('ROLE_USER');
        }
        $book ??= new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user = $this->getUser();
            if(!$book->getId() && $user instanceof User){
                $book->setCreatedBy($user);
            }
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('app_admin_book_show', ['id' => $book->getId()]);
        }
        return $this->render('admin/book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
