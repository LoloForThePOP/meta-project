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
     * The new Comment form is loaded in PPController (function : project show)
     * 
     * @Route("/new/", name="new_comment")
     * @Security("is_granted('ROLE_USER')")
     */
    public function new(PPBasic $presentation): Response
    {
        // the form is loaded in PPcontroller

        return $this->redirectToRoute('project_show', [
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
                '_fragment' => 'commentsDisplay',
                ]);
        }

        return $this->render('comments/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
        ]);
    }


}
