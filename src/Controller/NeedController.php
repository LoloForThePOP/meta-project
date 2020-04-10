<?php

namespace App\Controller;

use App\Entity\Need;
use App\Form\NeedType;
use App\Entity\PPBasic;
use App\Repository\NeedRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/needs")
 */
class NeedController extends AbstractController
{
    /**
     * @Route("/", name="need_index", methods={"GET"})
     */
    public function index(PPBasic $pp, NeedRepository $needRepository): Response
    {
        return $this->render('need/index.html.twig', [
            'needs' => $needRepository->findBy([
            'presentation' => $pp->getId(),
            ]),
            'slug' => $pp->getSlug(),
        ]);
    }

    /**
     * @Route("/new", name="need_new", methods={"GET","POST"})
     */
    public function new(PPBasic $pp, $slug, Request $request): Response
    {
        $need = new Need();
        $form = $this->createForm(NeedType::class, $need);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $need->setPresentation($pp);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($need);
            $entityManager->flush();

            return $this->redirectToRoute('need_index', [
                'slug' => $slug,
                ]);
        }

        return $this->render('need/new.html.twig', [
            'need' => $need,
            'form' => $form->createView(),
            'slug' => $pp->getSlug(),
        ]);
    }

    /**
     * @Route("/{id}", name="need_show", methods={"GET"})
     */
    public function show($slug, Need $need): Response
    {
        return $this->render('need/show.html.twig', [
            'need' => $need,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="need_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit($slug, Request $request, Need $need): Response
    {
        $form = $this->createForm(NeedType::class, $need);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('need_index',[
                'slug' => $slug,
            ]);
        }

        return $this->render('need/edit.html.twig', [
            'need' => $need,
            'form' => $form->createView(),
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/{id}", name="need_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete($slug, Request $request, Need $need): Response
    {
        if ($this->isCsrfTokenValid('delete'.$need->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($need);
            $entityManager->flush();
        }

        return $this->redirectToRoute('need_index',[
            'slug' => $slug,
        ]);
    }
}
