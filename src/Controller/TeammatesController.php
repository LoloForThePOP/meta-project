<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\Teammate;
use App\Form\TeammateType;
use App\Repository\TeammateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/teammates")
 * 
 * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
 */
class TeammatesController extends AbstractController
{
    /**
     * @Route("/", name="manage_teammates")
     * 
     */
    public function manage (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {
        $teammate = new Teammate();
        
        $form = $this->createForm(TeammateType::class, $teammate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $teammate->setProject($presentation);

            $manager->persist($teammate);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_teammates', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        return $this->render('teammates/manage.html.twig', [

            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
            
        ]);

    }

        
    /**
     * Allow to Edit a Teammate
     * 
     * @Route("/edit/{id_teammate}", name="edit_teammate")
     * 
     */
    public function edit (PPBasic $presentation, $id_teammate, TeammateRepository $teammateRepository, Request $request, EntityManagerInterface $manager)
    {

        $teammate = $teammateRepository->findOneById($id_teammate);

        $form = $this->createForm(TeammateType::class, $teammate);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            //$teammate->setProject($teammate);

            $manager->persist($teammate);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont bien été effectuées !"
            );

            return $this->redirectToRoute('manage_teammates', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }
    
        return $this->render('teammates/edit.html.twig', [
            'form' => $form->createView(),
            'teammate' => $teammate,
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }

 
    /**
     * Allow to modify Teammates positions with an ajax request
     *
     * @Route("/ajax-reorder-teammates/", name="ajax_reorder_teammates")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
    */ 
    public function ajaxReorderTeammates(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonTeammatesPositions = $request->request->get('jsonTeammatesPositions');

            $teammatesPositions = json_decode($jsonTeammatesPositions,true);

            foreach ($presentation->getTeammates() as $teammate){

                $newTeammatePosition = array_search($teammate->getId(), $teammatesPositions, false);
                
                $teammate->setPosition($newTeammatePosition);

                $manager->persist($teammate);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }

    
    /**
     * Allow to remove a Teammate (with an ajax request)
     * 
     * @Route("/ajax-remove-teammate/", name="ajax_remove_teammate")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function ajaxRemoveTeammate(PPBasic $presentation, Request $request, TeammateRepository $teammateRepository, EntityManagerInterface $manager){

        if ($request->isXmlHttpRequest()) {

            $idTeammate = $request->request->get('idTeammate');

            $teammate = $teammateRepository->findOneById($idTeammate);

            if ($presentation->getTeammates()->contains($teammate)) {

                $presentation->removeTeammate($teammate);
                
                $manager->remove($teammate);

                $manager->persist($presentation);

                $manager->flush();
            }

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }


}
