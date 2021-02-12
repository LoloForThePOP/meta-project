<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\PPBasic;
use App\Form\CommentType;
use App\Form\PPBasicType;
use App\Form\ReplyCommentType;
use App\Form\NewPresentationType;
use App\Service\ImageEditService;
use App\Repository\PPBasicRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ExternalContributorsStructure;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ExternalContributorsStructureType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PPController extends AbstractController
{
    /**
     * @Route("/projects", name="projects_index")
     */
    public function index(EntityManagerInterface $manager)
    {
        // $presentations = $repo->findAll();

        $lastInsertedProjects = $manager->createQuery('SELECT p FROM App\Entity\PPBasic p WHERE p.isPublished=true AND p.overallQualityAssessment>=3 AND p.adminValidation=true ORDER BY p.createdAt DESC')->setMaxResults('10')->getResult();

        return $this->render('pp/index.html.twig', [
            'presentations' => $lastInsertedProjects,
        ]);
    }

    
    /**
     * Permet de Créer une Présentation de Projet
     * 
     * @Route("/projects/new",name="create_presentation")
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function create (Request $request, EntityManagerInterface $manager){

        $presentation = new PPBasic();

        $form = $this->createForm(NewPresentationType::class, $presentation, array(

            // Time protection
            'antispam_time'     => true,
            'antispam_time_min' => 8,
            'antispam_time_max' => 3600,
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $presentation->setCreator($this->getUser());

            $manager->persist($presentation);
            $manager->flush();

            // initialize a unique slug

            $presentation->setSlug($presentation->getSlug().'-'.$presentation->getId());
            $manager->persist($presentation);
            $manager->flush();

            $this->addFlash(
                'success',
                'La présentation du projet a été créée ! <br> Vous pouvez maintenant ajouter des informations que vous désirez présenter.'
            );

            return $this->redirectToRoute('edit_presentation_menu', [
                'slug' => $presentation->getSlug()
            ]);
        }

        return $this->render('pp/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Allow to edit project title or goal
     * 
     * @Route("/projects/{slug}/edit/", name="project_edit")
     * 
     * @return void
     */
    public function edit (PPBasic $presentation, Request $request, EntityManagerInterface $manager, ImageEditService $editImageService ) {

        $this->denyAccessUnlessGranted('edit', $presentation);

        $form = $this->createForm(PPBasicType::class, $presentation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($presentation);
            $manager->flush();

            $editImageService->edit('presentation_logo', $presentation->getLogoName());

            $this->addFlash(
                'success',
                "Les modifications de la présentation ont été enregistrées."
            );

            return $this->redirectToRoute('edit_presentation_menu', [
                'slug' => $presentation->getSlug()
            ]);
        }

        return $this->render('pp/edit.html.twig', [
            'form'=> $form->createView(),
            'presentation' => $presentation,
        ]);

    }

    
    
     /**
     * Allow to delete a project presentation
     * 
     * @Route("projects/{slug}/delete",name="project_delete")
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator() ")
     * @return Response
     */
    public function delete(PPBasic $presentation, EntityManagerInterface $manager){

        $manager->remove($presentation);
        $manager->flush();

        $this->addFlash(
            'success',
            "La Présentation « {$presentation->getGoal()} » a été supprimée"
        );

        return $this->redirectToRoute('projects_index');
    }


    
    /**
     * Allow to Display a Project Presentation Page
     *
     * @Route("/projects/{slug}", name="project_show")
     * 
     * @return Response
     */
    public function show(PPBasic $presentation, Request $request){

        $user = $this->getUser();

        $this->denyAccessUnlessGranted('view', $presentation);

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, array(
            // Time protection
            'antispam_time'     => true,
            'antispam_time_min' => 10,
            'antispam_time_max' => 3600,
        
            // Honeypot protection
            'antispam_honeypot'       => true,
            'antispam_honeypot_class' => 'onpt',
            'antispam_honeypot_field' => 'email-repeat',
        ));

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
                '_fragment' => 'commentsDisplay',
                ]);
        }

         //reply to a comment form 

        $replyComment = new Comment();

        $replyForm = $this->createForm(ReplyCommentType::class, $replyComment, array(
            // Time protection
            'antispam_time'     => true,
            'antispam_time_min' => 10,
            'antispam_time_max' => 3600,
        
            // Honeypot protection
            'antispam_honeypot'       => true,
            'antispam_honeypot_class' => 'onpt',
            'antispam_honeypot_field' => 'email-repeat',
        ));

        $replyForm->handleRequest($request);

        if ($replyForm->isSubmitted() && $replyForm->isValid()) {

            //we retrieve parent comment

            $parentCommentId = $replyForm->get('parentCommentId')->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $parentComment = $entityManager->getRepository(Comment::class)->findOneById($parentCommentId);

            $replyComment->setParent($parentComment);

            $replyComment->setPresentation($presentation)
                    ->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($replyComment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre commentaire est ajouté.'
            );

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
                '_fragment' => 'commentsDisplay',
                ]);
        }

        return $this->render('/pp/show.html.twig',[
            'presentation' => $presentation,
            'form' => $form->createView(),
            'replyForm' => $replyForm->createView(),
        ]);

    }
    
    /**
     * Allow to Display a Project Presentation Page by its id
     *
     * @Route("/project/{id}", name="project_show_by_id", requirements={"id_presentation"="\d+"})
     * @Security("presentation.getIsPublished() == true or user === presentation.getCreator() ")
     * 
     * @return Response
     */
    public function showById(PPBasic $presentation){

        return $this->redirectToRoute('project_show', [
            'slug' => $presentation->getSlug(),
            ]);

    }


    /**
     * Allow to Display Project Presentation Edition Menu
     *
     * @Route("/projects/{slug}/edition-menu",name="edit_presentation_menu")
     * 
     * @return Response
     */
    public function showEditionMenu($slug, PPBasic $presentation, Request $request, EntityManagerInterface $manager){

        $this->denyAccessUnlessGranted('edit', $presentation);

        /* External contributors structures can be created directly from the edition menu */

        $ecs = new ExternalContributorsStructure();
        
        $ecsForm = $this->createForm(ExternalContributorsStructureType::class, $ecs);

        $ecsForm->handleRequest($request);


        if ($ecsForm->isSubmitted() && $ecsForm->isValid()){

            $ecs->setProject($presentation);

            $manager->persist($ecs);

            $manager->flush();

            $idECS = $ecs->getId();

            return $this->redirectToRoute('manage_ecs', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
                'id_ecs' => $idECS,
            ]);

        }

        return $this->render('/pp/edition_menu/structure.html.twig',[
            
            'ecsForm' => $ecsForm->createView(),
            'presentation' => $presentation,
        ]);

    }

}
