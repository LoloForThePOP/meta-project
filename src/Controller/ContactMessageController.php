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
    public function index(PPBasic $presentation,ContactMessageRepository $contactMessageRepository): Response
    {

        $this->denyAccessUnlessGranted('edit', $presentation);
        
        $projectMessages = $contactMessageRepository->findBy
            (
                [
                    'presentation' => $presentation->getId(),
                ],
                [
                    'createdAt' => 'DESC',
                ],
                
            );

        return $this->render('contact_message/index.html.twig', [
                'contact_messages' => $projectMessages,
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
        ]);
    }

    
    /** 
     * Allow to Display a Private Message (example : in a Modal Box).
     * 
     * @Route("/ajaxShowMessage", name="ajax_get_message_content") 
     * 
    */ 
    public function ajaxMessageContent(Request $request, ContactMessageRepository $contactMessageRepository, EntityManagerInterface $manager, PPBasic $presentation) {

        $this->denyAccessUnlessGranted('edit', $presentation);


        if ($request->isXmlHttpRequest()) {

            $messageId = $request->request->get('messageId');

            $contactMessage = $contactMessageRepository->findOneById($messageId);

            $messageContent = $contactMessage->getContent();

            // we set the message has been consulted

            $contactMessage->setHasBeenConsulted(true);
            $manager->persist($contactMessage);
            $manager->flush();

            $dataResponse = [
                'messageContent' => $messageContent,
            ];

            return new JsonResponse($dataResponse);
            
        }

    }

    

    /**
     * Allow to Delete a Private Message
     * 
     * @Route("/ajaxDeleteMessage/", name="ajax_delete_message")
     * 
     * @return Response
     */
   public function deleteMessage(Request $request, PPBasic $presentation, EntityManagerInterface $manager, ContactMessageRepository $contactMessageRepository){

        $this->denyAccessUnlessGranted('edit', $presentation);


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
     * Allow to create and send a private message
     * @Route("/new/", name="contact_message_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function new (PPBasic $pp, Request $request, \Swift_Mailer $mailer): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contactMessage->setPresentation($pp);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactMessage);
            $entityManager->flush();
            
            $this->addFlash(
                'success',
                "Votre Message a été envoyé"
            );

            //die( dump($pp->getCreator()->getEmail()));

            
            // we create an e-mail for project creator
            $message = (new \Swift_Message('Nouveau Message Reçu'))
                ->setFrom(['noreply@projetdesprojets.fr'=>'Projet des Projets'])
                ->setTo($pp->getCreator()->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/newPrivateMessage.html.twig',[

                            'presentation' => $pp,
                        ]
                    ),
                    'text/html'
                )
            ;

            // On envoie l'e-mail
            $mailer->send($message);

            return $this->redirectToRoute('project_show', [
                'slug' => $pp->getSlug(),
            ]);
        }

        return $this->render('contact_message/new.html.twig', [
            'contact_message' => $contactMessage,
            'slug' => $pp->getSlug(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Allow to Show a Private Message
     * 
     * @Route("/{id}", name="contact_message_show", methods={"GET"})
     * 
     */
    public function show(ContactMessage $contactMessage): Response
    {
        $this->denyAccessUnlessGranted('edit', $contactMessage->getPresentation());

        return $this->render('contact_message/show.html.twig', [
            'contact_message' => $contactMessage,
        ]);
    }


  
}
