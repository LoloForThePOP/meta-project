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
        return $this->render('settings/index.html.twig', [
            'presentation' => $presentation,
        ]);
    }

          
    /** 
     * Allow to switch Contact Messages Activation
     * 
     * @Route("/ajax-contact-messages-activation", name="ajax_contact_messages_activation") 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
    */ 

    public function ajaxContMessActivation(Request $request, PPBasic $presentation,EntityManagerInterface $manager) {

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
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
    */ 

    public function ajaxPublishPresentationSwitch(Request $request, PPBasic $presentation,EntityManagerInterface $manager) {

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


}
