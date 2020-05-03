<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PGroup;
use App\Entity\PPBasic;
use App\Repository\PGroupRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PGroupController extends AbstractController
{
    /**
     * @Route("/projects/groups", name="projects_groups_index")
     */
    public function index(PGroupRepository $pGroupRepo)
    {
        $projectGroups = $pGroupRepo->findAll();

        return $this->render('pgroup/index.html.twig', [
            'pGroups' => $projectGroups,
        ]);
    }

    /**
     * Allow to Display a Group Presentation Page
     *
     * @Route("/projects/groups/{id}",name="project_group_show")
     * 
     * @return Response
     */
    public function show(PGroup $pGroup){

        return $this->render('/pgroup/show.html.twig',[
            'pGroup' => $pGroup,
        ]);

    }

    /**
     * Allow to Apply for Group Integration (e.g. insert a candidate project into a project group)
     *
     * @Route("/projects/groups/{id_group}/{id_user}/add-candidate/",name="group_add_candidate")
     * 
     * @Entity("pGroup", expr="repository.find(id_group)")
     * @Entity("user", expr="repository.find(id_user)")
     * 
     * @return Response
     */

   public function addCandidateP(PGroup $pGroup, User $user){

        return $this->render('/pgroup/add_candidate.html.twig',[
            'pGroup' => $pGroup,
            'user' => $user,
        ]);

    }




}
