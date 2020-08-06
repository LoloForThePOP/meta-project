<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\PPBasic;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/projects/{slug}/events")
 */
class EventController extends AbstractController
{
    
    /**
     * @Route("/manage", name="manage_events")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function manage (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {
        $event = new Event ();
        
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $beginYear = $form->get('beginYear')->getData();

            if($beginYear){
               die( dump($beginYear ));
            }

            $event->setProject($presentation);

            $manager->persist($event);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_events', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        return $this->render('events/manage.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);

    }

       
    /**
     * Allow to Edit an Event
     * 
     * @Route("/edit/{idEvent}", name="edit_event")
     */
    public function edit (PPBasic $presentation, $idEvent, eventRepository $eventRepo, Request $request, EntityManagerInterface $manager)
    {

        $event = $eventRepo->findOneById($idEvent);

        $form = $this->createForm(EventType::class, $event);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $event->setProject($presentation);

            $manager->persist($event);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont bien été effectuées !"
            );

            return $this->redirectToRoute('manage_events', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }
    
        return $this->render('events/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }

       
    /**
     * Allow to remove an event (with an ajax request)
     * 
     * @Route("/ajax-remove-event/", name="ajax_remove_event")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function ajaxRemovEvent(PPBasic $presentation, Request $request, EventRepository $eventRepository, EntityManagerInterface $manager){

        if ($request->isXmlHttpRequest()) {

            $idEvent = $request->request->get('idEvent');

            $event = $eventRepository->findOneById($idEvent);

            if ($presentation->getEvents()->contains($event)) {

                $presentation->removeEvent($event);
                
                $manager->remove($event);

                $manager->persist($presentation);

                $manager->flush();
            }

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }




}
