<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Form\PPBasicType;
use App\Repository\PPBasicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PPController extends AbstractController
{
    /**
     * @Route("/projects", name="projects_index")
     */
    public function index(PPBasicRepository $repo)
    {
        $presentations = $repo->findAll();

        return $this->render('pp/index.html.twig', [
            'presentations' => $presentations,
        ]);
    }

    
    /**
     * Permet de Créer une Présentation
     * 
     * @Route("/projects/new",name="projects_create")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function create (Request $request, EntityManagerInterface $manager){

        $presentation = new PPBasic();

        $form = $this->createForm(PPBasicType::class,$presentation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $presentation->setCreator($this->getUser());

            $manager->persist($presentation);
            $manager->flush();

            $this->addFlash(
                'success',
                "La Présentation {$presentation->getTitle()} a bien été Créée!"
            );

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug()
            ]);
        }

        return $this->render('pp/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Permet d'éditer une présentation de projet
     * 
     * @Route("/projects/{slug}/edit/", name="project_edit")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator() ",message="Cette Annonce ne vous appartient pas, vous ne pouvez pas la modifier")
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
                "Les Modifications de la Présentation du Projet {$presentation->getTitle()} ont bien été enregistrées."
            );

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug()
            ]);
        }

        return $this->render('pp/edit.html.twig', [
            'form'=> $form->createView(),
            'presentation' => $presentation,
        ]);

    }

    
    
     /**
     * Permet de Supprimer une Présentation de Projet
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
            "La Présentation {$presentation->getTitle()} a bien été supprimée"
        );

        return $this->redirectToRoute('projects_index');
    }

    /**
     * Permet d'afficher une page de présentation de projet
     *
     * @Route("/projects/{slug}",name="project_show")
     * 
     * @return Response
     */
    public function show($slug, PPBasic $presentation){

        return $this->render('/pp/show.html.twig',[
            'presentation' => $presentation,
        ]);

    }

}