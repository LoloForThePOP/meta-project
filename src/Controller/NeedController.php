<?php

namespace App\Controller;

use App\Entity\Need;
use App\Form\NeedType;
use App\Entity\PPBasic;
use App\Repository\NeedRepository;
use App\Entity\PPMajorLogs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
            'needs' => 
                $needRepository->findBy([
                    'presentation' => $pp->getId(),
                ]),
            'slug' => $pp->getSlug(),
            'presentation' => $pp,
        ]);
    }

    /**
     * @Route("/new/{needType}", name="need_new", methods={"GET","POST"})
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function new(PPBasic $presentation, $needType, $slug, Request $request, EntityManagerInterface $manager): Response
    {

        $need = new Need();

        $form = $this->createForm(NeedType::class, $need)->remove('type');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $need->setType($needType)
                 ->setPresentation($presentation);

            $manager->persist($need);
            $manager->flush();

            $idNeed=$need->getId();

            PPMajorLogs::updateLogs($presentation, 'need', 'new', $idNeed, $manager);

            return $this->redirectToRoute('need_index', [
                'slug' => $slug,
                ]);
        }

        return $this->render('need/new.html.twig', [
            'need' => $need,
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
        ]);
    }

     
    /** 
     * Allow to Get Need Details and Display them (ajax request)
     * 
     * @Route("/ajaxShowNeed", name="ajax_get_need_details") 
     *
    */ 
 
    public function ajaxNeedDetails(Request $request, NeedRepository $needRepository, EntityManagerInterface $manager, PPBasic $presentation) {

        if ($request->isXmlHttpRequest()) {

            $needId = $request->request->get('needId');

            // We get Need's Details we'd like to Show

            $need = $needRepository->findOneById($needId);

            $needDescription = $need->getDescription();

            $needCreatedAt = $need->getCreatedAt();

            $needIsPaid = $need->getPaidService();

            $dataResponse = [

                'needDescription' => $needDescription,

                'needCreatedAt' => $needCreatedAt,

                'needIsPaid' => $needIsPaid,
                
            ];

            return new JsonResponse($dataResponse);
            
        }

    }




    /**
     * @Route("/{id}", name="need_show", methods={"GET"})
     */
    public function show($slug, Need $need): Response
    {
        return $this->render('need/show.html.twig', [
            'need' => $need,
            'slug' => $slug,
            'presentation' => $need->getPresentation(),
        ]);
    }

    /**
     * @Route("/{need_id}/edit", name="need_edit", methods={"GET","POST"})
     * 
     * @Entity("need", expr="repository.find(need_id)")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     */
    public function edit(PPBasic $presentation, Request $request, Need $need): Response
    {
        $form = $this->createForm(NeedType::class, $need);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('need_index',[
                'slug' => $presentation->getSlug(),
            ]);
        }

        return $this->render('need/edit.html.twig', [
            'need' => $need,
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="need_delete")
     * @Security("is_granted('ROLE_USER') and user === need.getPresentation().getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
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

    
    
    /**
     * Allow to modify Needs positions with an ajax request
     *
     * @Route("/ajax-reorder-needs/", name="ajax_reorder_needs")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
    */ 
    public function ajaxReorderNeeds(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonNeedsPositions = $request->request->get('jsonNeedsPositions');

            $needsPositions = json_decode($jsonNeedsPositions,true);

            foreach ($presentation->getNeeds() as $need){

                $newNeedPosition = array_search($need->getId(), $needsPositions, false);
                
                $need->setPosition($newNeedPosition);

                $manager->persist($need);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();
    }

    
}
