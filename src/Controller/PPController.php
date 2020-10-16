<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Form\PPBasicType;
use App\Form\NewPresentationType;
use App\Repository\PPBasicRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ExternalContributorsStructure;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ExternalContributorsStructureType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PPController extends AbstractController
{
    /**
     * @Route("/projects", name="projects_index")
     */
    public function index(EntityManagerInterface $manager)
    {
        // $presentations = $repo->findAll();

        $lastInsertedProjects = $manager->createQuery('SELECT p.title, p.keywords, p.thumbnailName, p.goal, p.slug, p.title FROM App\Entity\PPBasic p  WHERE p.isPublished=true ORDER BY p.createdAt DESC')->setMaxResults('10')->getResult();

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

        $form = $this->createForm(NewPresentationType::class,$presentation);

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
                'La présentation du projet a bien été créée ! <br> Vous pouvez maintenant ajouter toutes les informations que vous désirez présenter.'
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
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     *
     * @return void
     */
    public function edit (PPBasic $presentation, Request $request, EntityManagerInterface $manager) {

        $form = $this->createForm(PPBasicType::class, $presentation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($presentation);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de la présentation ont bien été enregistrées."
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
            "La Présentation « {$presentation->getTitle()} » a bien été supprimée"
        );

        return $this->redirectToRoute('projects_index');
    }

    /**
     * Allow to Display a Project Presentation Page
     *
     * @Route("/projects/{slug}",name="project_show")
     * 
     * @Security("presentation.getIsPublished() == true or user === presentation.getCreator() ")
     * 
     * @return Response
     */
    public function show($slug, PPBasic $presentation){

        return $this->render('/pp/show.html.twig',[
            'presentation' => $presentation,
        ]);

    }

    /**
     * Allow to Display Project Presentation Edition Menu
     *
     * @Route("/projects/{slug}/edition-menu",name="edit_presentation_menu")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator() ")
     * 
     * @return Response
     */
    public function showEditionMenu($slug, PPBasic $presentation, Request $request, EntityManagerInterface $manager){

        /* External contributors structures can be created directly from the edition menu */

        $ecs = new ExternalContributorsStructure();
        
        $ecsForm = $this->createForm(ExternalContributorsStructureType::class, $ecs);

        $ecsForm->handleRequest($request);


        if ($ecsForm->isSubmitted() && $ecsForm->isValid()){

            $ecs->setProject($presentation);

            $manager->persist($ecs);

            $manager->flush();

            $idECS = $ecs->getId();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

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

    
    /**
     * Allow to Display Project Presentation Links Page
     *
     * @Route("/projects/{slug}/links",name="presentation_links")
     * 
     * @return Response
     */
    public function links($slug, PPBasic $presentation){

        return $this->render('/pp/misc/links.html.twig',[
            'presentation' => $presentation,
        ]);

    }

}
