<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/admin/author')]
#[IsGranted('IS_AUTHENTICATED')]
final class AuthorController extends AbstractController
{
    #[Route('/', name: 'app_admin_author')]
    public function index(AuthorRepository $repository, Request $request): Response
    {
        $authors = $repository->findAll();

        $dates = [];
        if($request->query->get('start')){
            $dates['start'] = $request->query->get('start');
        }
        if($request->query->get('end')){
            $dates['end'] = $request->query->get('end');
        }
        $authors = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->findByDateOfBirth()),
            $request->query->get('page', 1),
            2
        );

        return $this->render('admin/author/index.html.twig', [
            'controller_name' => 'AuthorController',
            'authors' => $authors,
        ]);

    }

    #[Route('/{id}', name: 'app_admin_author_show',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Author $author): Response
    {
        
        return $this->render('admin/author/show.html.twig', [
            'author' => $author,
        ]);
    }

    
    #[Route('/new', name: 'app_admin_author_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_admin_author_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(?Author $author, Request $request, EntityManagerInterface $em): Response
    {
        if($author){
            if(!$this->isGranted('ROLE_ADMIN')){
                $this->denyAccessUnlessGranted('author.is_creator', $author);
            }
        }
        if(null === $author){
            $this->denyAccessUnlessGranted('ROLE_USER');
        }
        $author ??= new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();

            $this->addFlash('success', 'L\'auteur a été ajouté avec succès');
            return $this->redirectToRoute('app_admin_author_show', ['id' => $author->getId()]);
        }

        return $this->render('admin/author/new.html.twig', [
            'form' => $form,
        ]);
    }
}
