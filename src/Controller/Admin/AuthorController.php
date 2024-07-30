<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/author')]
class AuthorController extends AbstractController
{
    #[Route('', name: 'admin_author')]
    public function index(AuthorRepository $repository): Response
    {
            $authors = $repository->findAll();

        return $this->render('admin/author/index.html.twig', [
            'controller_name' => 'AuthorController',
            'authors' => $authors
        ]);
    }

    #[Route('/new', name: 'admin_author_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($author);
            $manager->flush();
            return $this->redirectToRoute('admin_author_new');
        }
        
        return $this->render('admin/author/new.html.twig', [
            'form' => $form,
        ]);
    }
}
