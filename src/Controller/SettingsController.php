<?php

namespace App\Controller;

use App\Entity\PPBasic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/settings")
 */
class SettingsController extends AbstractController
{
    /**
     * @Route("/", name="settings_index")
     */
    public function index(PPBasic $presentation)
    {

        $this->denyAccessUnlessGranted('edit', $presentation);

        return $this->render('settings/index.html.twig', [
            'presentation' => $presentation,
        ]);
    }

          
    /** 
     * Allow to switch Contact Messages Activation
     * 
     * @Route("/ajax-contact-messages-activation", name="ajax_contact_messages_activation") 
     * 
    */ 

    public function ajaxContMessActivation(Request $request, PPBasic $presentation,EntityManagerInterface $manager) {

        $this->denyAccessUnlessGranted('edit', $presentation);

        if ($request->isXmlHttpRequest()) {

            $jsonActivateContactMessages = $request->request->get('checkboxValue');

            $activateContactMessages = json_decode($jsonActivateContactMessages,true);


            $presentation->setIsActiveContactMessages($activateContactMessages);

            $manager->persist($presentation);

            $manager->flush();
          
            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);
        }

    }



    /** 
     * Allow to Keep Presentation as a Draft, or Publish it
     * 
     * @Route("/ajax-publish-presentation", name="ajax_publish_presentation_switch") 
     * 
    */ 

    public function ajaxPublishPresentationSwitch(Request $request, PPBasic $presentation,EntityManagerInterface $manager) {

        $this->denyAccessUnlessGranted('edit', $presentation);

        if ($request->isXmlHttpRequest()) {

            $jsonIsPublishedPresentation = $request->request->get('checkboxValue');

            $isPublishedPresentation = json_decode($jsonIsPublishedPresentation,true);

            $presentation->setisPublished($isPublishedPresentation);

            $manager->persist($presentation);

            $manager->flush();
          
            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);
        }

    }

    /** 
     * Allow or disallow comments for a project presentation
     * 
     * @Route("/ajax-allow-comments", name="ajax_allow_comments_switch")
     * 
    */ 

    public function ajaxDisableComments(Request $request, PPBasic $presentation,EntityManagerInterface $manager) {

        $this->denyAccessUnlessGranted('edit', $presentation);

        if ($request->isXmlHttpRequest()) {

            $jsonAllowComments = $request->request->get('checkboxValue');

            
            $allowComments = json_decode($jsonAllowComments,true);

            $presentation->setAllowComments($allowComments);

            $manager->persist($presentation);

            $manager->flush();
          
            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);
        }

    }


}
