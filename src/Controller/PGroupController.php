<?php

namespace App\Controller;

use App\Entity\PGroup;
use App\Repository\PGroupRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PGroupController extends AbstractController
{
    /**
     * @Route("/projects/groups", name="projects_groups_index")
     */
    public function index(PGroupRepository $pGroupRepo)
    {

        return $this->render('pgroup/index.html.twig', [
            'pGroups' => $pGroupRepo->findAll(),
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

}
