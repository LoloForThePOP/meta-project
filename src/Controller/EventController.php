<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\PPBasic;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Entity\PPMajorLogs;
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
     * 
     * Allow to add a new Event & display existing events
     * 
     * @Route("/manage", name="manage_events")
     * 
     */
    public function manage (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {   
        $this->denyAccessUnlessGranted('edit', $presentation);

        $event = new Event ();
        
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // set a virtual begin date to new event

           // $event->toVirtualDate($form->get('beginYear')->getData(), $form->get('beginMonth')->getData(), $form->get('beginDay')->getData());

           // set a virtual begin date to new event

            $event->setVirtualBeginDate(Event::toVirtualDate($form->get('beginYear')->getData(), $form->get('beginMonth')->getData(), $form->get('beginDay')->getData()));

            // set a virtual end date to new event

            $event->setVirtualEndDate(Event::toVirtualDate($form->get('endYear')->getData(), $form->get('endMonth')->getData(), $form->get('endDay')->getData()));

            //reorder project events according to the new event date

            $newEventDate = $event->getVirtualBeginDate();

            if ($newEventDate !== NULL) {

                foreach ($presentation->getEvents() as $index => $preExistEvent){

                    $updatedPosition = false;

                    $preExistEventDate = $preExistEvent -> getVirtualBeginDate();
                    
                    if ($newEventDate > $preExistEventDate) {
                        
                        $event->setPosition($index + 1);

                    }
                    
                    if ($newEventDate < $preExistEventDate) {
                        
                        $preExistEvent->setPosition($index + 1); 
                        
                        $manager->persist($preExistEvent);

                    }
                }
            
            
            }

            //die(dump($event->toStringDate_Fr(1990,13,NULL)));

            $event->setProject($presentation);

            $manager->persist($event);

            $manager->flush();

            $idEvent=$event->getId();

            PPMajorLogs::updateLogs($presentation, 'event', 'new', $idEvent, $this->getUser()->getId(), $manager);

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

        $this->denyAccessUnlessGranted('edit', $presentation);

        $event = $eventRepo->findOneById($idEvent);

        $form = $this->createForm(EventType::class, $event);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $event->setProject($presentation);

            $manager->persist($event);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
                '_fragment' => 'eventsDisplay',
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
     */
    public function ajaxRemovEvent(PPBasic $presentation, Request $request, EventRepository $eventRepository, EntityManagerInterface $manager){

        $this->denyAccessUnlessGranted('edit', $presentation);

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

      
    /**
     * Allow to modify project Dates & Events positions with an ajax request
     *
     * @Route("/ajax-reorder-events/", name="ajax_reorder_events")
     * 
    */ 
    public function ajaxReorderEvents(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        $this->denyAccessUnlessGranted('edit', $presentation);

        if ($request->isXmlHttpRequest()) {

            $jsonEventsPositions = $request->request->get('jsonEventsPositions');

            $eventsPositions = json_decode($jsonEventsPositions,true);

            foreach ($presentation->getEvents() as $event){

                $eventNewPosition = array_search($event->getId(), $eventsPositions, false);
                
                $event->setPosition($eventNewPosition);

                $manager->persist($event);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }


    /**
     * show new event details in user notification page
     * 
     * @Route("/event/ajax-show-embed", name="show_embed_event")
     * 
     */
    public function showEmbed(Request $request, EventRepository $eventRepository)
    {
        
        if ($request->isXmlHttpRequest()) {

            //get selected news

            $idEvent = $request->request->get('idEntity');

            $event = $eventRepository->findOneById($idEvent);

            $dataResponse = [

                'html' => $this->renderView(
                    
                    'events/show_embed.html.twig', 

                    [
                        'event' => $event,
                    ]
                ),
            ];

            //dump($dataResponse);

            return new JsonResponse($dataResponse);

        
        }

    }





}
