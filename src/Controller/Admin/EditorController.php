<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

#[Route("/admin/editor")]
final class EditorController extends AbstractController
{
    #[Route('/', name: 'app_admin_editor')]
    public function index(EditorRepository $repository, Request $request): Response
    {
        // $editors = $repository->findAll();
        $editors = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->createQueryBuilder('e')),
            $request->query->get('page', 1),
            5
        );
        return $this->render('admin/editor/index.html.twig', [
            'editors' => $editors,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_editor_show',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Editor $editor): Response
    {
        
        return $this->render('admin/editor/show.html.twig', [
            'editor' => $editor,
        ]);
    }

    #[Route('/new', name: 'app_admin_editor_new')]
    #[Route('/{id}/edit', name: 'app_admin_editor_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(?Editor $editor, Request $request, EntityManagerInterface $em): Response
    {
        $editor ??= new Editor();

        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($editor);
            $em->flush();
            return $this->redirectToRoute('app_admin_editor_show', ['id' => $editor->getId()]);
        }
        return $this->render('admin/editor/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
