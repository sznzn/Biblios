<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/author')]
class AuthorController extends AbstractController
{
    #[Route('', name: 'admin_author')]
    public function index(Request $request, AuthorRepository $repository): Response
    {

        //search
        $dates = [];
        if($request->query->has('start')){
            $dates['start'] = $request->query->get('start');
        }

        if($request->query->has('end')){
            $dates['end'] = $request->query->get('end');
        }

        //Pagination
        $authors = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->findByDateOfBirth()),
            $request->query->get(key: 'page', default:1),
            maxPerPage:10
        );

        //page de authors
        // $authors = $repository->findAll();

        return $this->render('admin/author/index.html.twig', [
            'controller_name' => 'AuthorController',
            'authors' => $authors
        ]);

        
    }

    #[Route('/new', name: 'admin_author_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'admin_author_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(?Author $author, Request $request, EntityManagerInterface $manager): Response
    {
        $author ??= new Author();
        //如果$author是null 那么就是 $author = new Author(), 如果不是，那么就保留$author本身的值
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($author);
            $manager->flush();
            return $this->redirectToRoute('admin_author_show', ['id' => $author->getId()]);
        }
        
        return $this->render('admin/author/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_author_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Author $author): Response
    {
        return $this->render('admin/author/show.html.twig', [
            'author' => $author
        ]);
    }
}
