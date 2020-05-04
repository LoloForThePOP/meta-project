<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PGroup;
use App\Entity\PPBasic;
use App\Form\PGroupType;
use App\Form\GroupAddCandidateType;
use App\Repository\PGroupRepository;
use App\Repository\PPBasicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/projects/groups")
 */

class PGroupController extends AbstractController
{
    /**
     * @Route("/", name="projects_groups_index")
     */
    public function index(PGroupRepository $pGroupRepo)
    {
        $projectGroups = $pGroupRepo->findAll();

        return $this->render('pgroup/index.html.twig', [
            'pGroups' => $projectGroups,
        ]);
    }

    
    /**
     * Allow to Create a New Project Group
     * 
     * @Route("/projects/groups/new/", name="project_group_new")
     *  
     */
    public function create(Request $request)
    {
        $group = new PGroup();
        $form = $this->createForm(PGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $group->setCreator($user);
            $group->addMaster($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();

            $newGroupId = $group->getId();

            
            $this->addFlash(
                'success',
                "Le Groupe de Projet a bien été Créé. Vous pouvez maintenant inclure des projets dedans."
            );

            return $this->redirectToRoute('project_group_show', [
                'id_group' => $newGroupId,
                ]);
        } 

        return $this->render('pgroup/new.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

    /**
     * Allow to Display a Group Presentation Page
     *
     * @Route("/{id_group}",name="project_group_show", requirements={"id_group":"\d+"})
     * @Entity("pGroup", expr="repository.find(id_group)")
     * 
     * @return Response
     */
    public function show(PGroup $pGroup){

        return $this->render('/pgroup/show.html.twig',[
            'pGroup' => $pGroup,
        ]);

    }

    /**
     * Display a list of projects that user can integrate into a group
     *
     * @Route("/{id_group}/{id_user}/add-candidate/",name="group_add_candidate_list")
     * 
     * @Entity("pGroup", expr="repository.find(id_group)")
     * @Entity("user", expr="repository.find(id_user)")
     * 
     * @return Response
     */

    public function indexUserCandidateP(PGroup $pGroup, User $user){

        return $this->render('/pgroup/add_candidate.html.twig',[
            'pGroup' => $pGroup,
            'user' => $user,
        ]);

    }

    /**
     * Manage group integration of a project (it becomes a group candidate project)
     *
     * @Route("/{id_group}/{id_user}/add-candidate/{id_project}",name="group_add_candidate_list")
     * 
     * @Entity("pGroup", expr="repository.find(id_group)")
     * @Entity("user", expr="repository.find(id_user)")
     * @Entity("user", expr="repository.find(id_project)")
     * 
     * @return Response
     */

    public function addCandidateP(PGroup $pGroup, PPBasic $project, User $user, EntityManagerInterface $manager){

        return $this->render('/pgroup/add_candidate.html.twig',[
            'pGroup' => $pGroup,
            'user' => $user,
        ]);

    }


}
