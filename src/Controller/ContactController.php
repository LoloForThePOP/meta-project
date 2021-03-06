<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\PPBasic;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/contacts")
 * 
 * Allow user to manage its presentation contact cards (ex : telephone; address; email; ...)
 * 
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact_index", methods={"GET"})
     */
    public function index(PPBasic $presentation, ContactRepository $contactRepository): Response
    {
        $this->denyAccessUnlessGranted('edit', $presentation);

        return $this->render('contacts/index.html.twig', [
            'contacts' => $contactRepository->findBy([
                'presentation' => $presentation->getId(),
                ]),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }

    /**
     * @Route("/new", name="contact_new", methods={"GET","POST"})
     * 
     */
    public function new(Request $request, PPBasic $presentation): Response
    {

        $this->denyAccessUnlessGranted('edit', $presentation);

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $contact->setPresentation($presentation);
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
                '_fragment' => 'contactInfosDisplay',
            ]);
        }

        return $this->render('contacts/new.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_show", methods={"GET"})
     */
    public function show(Contact $contact, $slug): Response
    {
        return $this->render('contacts/show.html.twig', [
            'contact' => $contact,
            'slug' => $slug,
        ]);
    }


    /**
     * @Route("/{contact_id}/edit", name="contact_edit", methods={"GET","POST"})
     * 
     * @Entity("contact", expr="repository.find(contact_id)")
     */
    public function edit(PPBasic $presentation, Request $request, Contact $contact): Response
    {

        $this->denyAccessUnlessGranted('edit', $presentation);


        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
                '_fragment' => 'contactInfosDisplay',
            ]);
        }

        return $this->render('contacts/edit.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_delete", methods={"DELETE"})
     */
    public function delete($slug, Request $request, Contact $contact): Response
    {

        $this->denyAccessUnlessGranted('edit', $contact->getPresentation());

        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_index', [
            'slug' => $slug,
        ]);
    }

        
    /**
     * Allow to reorder contact caards positions with an ajax request
     *
     * @Route("/ajax-reorder-contact-cards/", name="ajax_reorder_contact_cards")
     * 
    */ 
    public function ajaxReorderContactCards(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        $this->denyAccessUnlessGranted('edit', $presentation);

        if ($request->isXmlHttpRequest()) {

            $jsonContactCardsPosition = $request->request->get('jsonContactCardsPosition');

            $contactCardsPosition = json_decode($jsonContactCardsPosition,true);

            foreach ($presentation->getContacts() as $contact){

                $newContactCardPosition = array_search($contact->getId(), $contactCardsPosition, false);
                
                $contact->setPosition($newContactCardPosition);

                $manager->persist($contact);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }












}
