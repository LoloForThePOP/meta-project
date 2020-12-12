<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LinksController extends AbstractController
{
    
    /**
     * 
     * some helpers for users in order to export or link its project presentation page
     * 
     * for example, give code to user to iframe its presentation into another website or get link to its presentation
     * 
     * @Route("/projects/{slug}/links", name="links_index")
     */
    public function links($slug, PPBasic $presentation)
    {
        $iFrameURL = $this->generateUrl('project_show_embed', array('id' => $presentation->getId()), UrlGeneratorInterface::ABSOLUTE_URL);

        $projectPresentationURL = $this->generateUrl('project_show_by_id', array('id' => $presentation->getId()), UrlGeneratorInterface::ABSOLUTE_URL);

        $userId = $this->getUser()->getId();

        $userProjectsListURL = $this->generateUrl('user_public_project_list', array('idUser' => $userId), UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('links/index.html.twig', [
            
            'presentation' => $presentation,
    
            'iFrameURL' => $iFrameURL,

            'projectPresentationURL' => $projectPresentationURL,
            'userProjectsListURL' => $userProjectsListURL,
            
        ]);
    }

    /**     
     * Allow to display user projects list 
     * @Route("/user-public-project-list/{idUser}",name="user_public_project_list")
     *
     * @return Response
     */
    public function showUserProjectList ($idUser, UserRepository $user) {

        $user = $user->findOneById($idUser);
        
        return $this->render('links/user_project_list.html.twig', [
            'user' => $user,
            // s'assurer que l'on ne montre que des informations publiques dans le template
        ]);
    }


    
    /**
     * Allow to Display a Project Presentation Page without sidebars and other external contents and (purpose example : for export in iframes)
     *
     * @Route("/projects/{id}/embed", name="project_show_embed")
     * 
     * @Security("presentation.getIsPublished() == true or user === presentation.getCreator() ")
     * 
     * @return Response
     */
    public function showEmbed(PPBasic $presentation){

        return $this->render('/links/presentation_display_embed.html.twig',[
            'presentation' => $presentation,
        ]);

    }

    
    /**
     * for test purpose : test a Project Presentation Display into a Fake external webpage (within an iFrame)
     *
     * @Route("/testiframes", name="project_test_iframe")
     * 
     * @return Response
     */
    public function testIFrame(){

        return $this->render('/links/fake_external_website.html.twig',[
          
        ]);

    }

}
