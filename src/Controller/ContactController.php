<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\PPBasic;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/contacts")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact_index", methods={"GET"})
     */
    public function index(PPBasic $presentation, ContactRepository $contactRepository): Response
    {

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
     * @Security("is_granted('ROLE_USER')")
     * 
     */
    public function new(Request $request, PPBasic $presentation): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $contact->setPresentation($presentation);
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('contact_index',[
                'slug' => $presentation->getSlug(),
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
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     */
    public function edit(PPBasic $presentation, Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_index',[
                'slug' => $presentation->getSlug(),
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
     *  @Security("is_granted('ROLE_USER') and user === contact.getPresentation().getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     */
    public function delete($slug, Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_index', [
            'slug' => $slug,
        ]);
    }
}
