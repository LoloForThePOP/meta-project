<?php

namespace App\Controller;

use App\Entity\PPBasic;
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

        return $this->render('links/index.html.twig', [
            
            'presentation' => $presentation,
    
            'iFrameURL' => $iFrameURL,
            'projectPresentationURL' => $projectPresentationURL,
            
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
