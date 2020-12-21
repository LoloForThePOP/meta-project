<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\PPBasic;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/projects/{slug}/comments")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/index", name="index_comment")
     */
    public function index(): Response
    {
        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }



    /**
     * @Route("/new", name="new_comment")
     * @Security("is_granted('ROLE_USER')")
     */
    public function new(PPBasic $presentation, Request $request): Response
    {
        $user = $this->getUser();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setPresentation($presentation)
                    ->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre commentaire est ajouté.'
            );

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
                ]);
        }


        return $this->render('comments/new.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
        ]);
    }

    /**
     * @Route("/edit/{id_comment}", name="edit_comment")
     * @Entity("comment", expr="repository.find(id_comment)")
     * @Security("is_granted('ROLE_USER') and user === comment.getUser()", message="Vous n'êtes pas le créateur de ce commentaire")
     */
    public function edit(PPBasic $presentation, Comment $comment, Request $request): Response
    {

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Votre commentaire est modifié.'
            );

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
                ]);
        }

        return $this->render('comments/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
        ]);
    }


}
