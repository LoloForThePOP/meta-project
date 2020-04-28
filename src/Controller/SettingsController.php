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
     * @Route("/ajaxContactMessagesActivation", name="ajax_contact_messages_activation") 
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


}
