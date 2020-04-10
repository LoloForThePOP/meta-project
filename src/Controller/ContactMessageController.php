<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactMessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/messages")
 */
class ContactMessageController extends AbstractController
{
    /**
     * @Route("/", name="index_project_messages", methods={"GET"})
     */
    public function index(PPBasic $presentation, ContactMessageRepository $contactMessageRepository): Response
    {
        $projectMessages = $contactMessageRepository->findBy
            ([
            'presentation' => $presentation->getId(),
            ]);

        return $this->render('contact_message/index.html.twig', [
                'contact_messages' => $projectMessages,
                'slug' => $presentation->getSlug(),
        ]);
    }

    
    /** 
     * @Route("/ajaxShowMessage", name="ajax_get_message_content") 
    */ 
    public function ajaxMessageContent(Request $request, ContactMessageRepository $contactMessageRepository, PPBasic $presentation) {

        if ($request->isXmlHttpRequest()) {

            $messageId = $request->request->get('messageId');

            $contactMessage = $contactMessageRepository->findOneById($messageId);

            $messageContent = $contactMessage->getContent();

            $dataResponse = [
                'messageContent' => $messageContent,
            ];

            return new JsonResponse($dataResponse);
            
        }

    }

    

    /**
     * Permet de Supprimer un Message
     * 
     * @Route("/ajaxDeleteMessage/", name="ajax_delete_message")
     * 
     * @return Response
     */
   public function deleteMessage(Request $request, PPBasic $presentation, EntityManagerInterface $manager, ContactMessageRepository $contactMessageRepository){

        if ($request->isXmlHttpRequest()) {

            $messageId = $request->request->get('messageId');

            $message = $contactMessageRepository->findOneById($messageId);

            $feedbackCode = false;

            if ($presentation->getContactMessages()->contains($message)) {

                $presentation->removeContactMessage($message);
                
                $manager->remove($message);

                $manager->persist($presentation);
                $manager->flush();

                $feedbackCode = true;
            }

            $dataResponse = [
                'feedbackCode' => $feedbackCode,
            ];

            return new JsonResponse($dataResponse);

        }
    }

    
    /**
     * @Route("/new", name="contact_message_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new(Request $request, $slug): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactMessage);
            $entityManager->flush();

            return $this->redirectToRoute('contact_message_index');
        }

        return $this->render('contact_message/new.html.twig', [
            'contact_message' => $contactMessage,
            'slug' => $slug,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_message_show", methods={"GET"})
     */
    public function show(ContactMessage $contactMessage): Response
    {
        return $this->render('contact_message/show.html.twig', [
            'contact_message' => $contactMessage,
        ]);
    }

  
}
