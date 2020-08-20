<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\TechnicalData;
use App\Form\TechnicalDataType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TechnicalDataRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/technicalData")
 * 
 * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
 */
class TechnicalDataController extends AbstractController
{
    /**
     * @Route("/", name="manage_technicalData")
     * 
     */
    public function manage (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {
        $techData = new TechnicalData();
        
        $form = $this->createForm(TechnicalDataType::class, $techData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $techData->setPresentation($presentation);

            $manager->persist($techData);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_technicalData', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        return $this->render('technical_data/manage.html.twig', [

            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
            
        ]);

    }

        
    /**
     * Allow to Edit a Technical Data
     * 
     * @Route("/edit/{id_technicalData}", name="edit_technicalData")
     * 
     */
    public function edit (PPBasic $presentation, $id_technicalData, TechnicalDataRepository $techDataRepository, Request $request, EntityManagerInterface $manager)
    {

        $techData = $techDataRepository->findOneById($id_technicalData);

        $form = $this->createForm(TechnicalDataType::class, $techData);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $techData->setPresentation($techData);

            $manager->persist($techData);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont bien été effectuées !"
            );

            return $this->redirectToRoute('manage_technicalData', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }
    
        return $this->render('technical_data/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }

 
    /**
     * Allow to modify Technical Data positions with an ajax request
     *
     * @Route("/ajax-reorder-techdata/", name="ajax_reorder_technicalDatas")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
    */ 
    public function ajaxReorderTechDatas(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonTechDatasPositions = $request->request->get('jsonTechDatasPositions');

            $techDatasPositions = json_decode($jsonTechDatasPositions,true);

            foreach ($presentation->getTechnicalData() as $techData){

                $newTechDataPosition = array_search($techData->getId(), $techDatasPositions, false);
                
                $techData->setPosition( $newTechDataPosition);

                $manager->persist($techData);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }

    
    /**
     * Allow to remove a Technical Data (with an ajax request)
     * 
     * @Route("/ajax-remove-techdata/", name="ajax_remove_technicalData")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function ajaxRemoveTechData(PPBasic $presentation, Request $request, TechnicalDataRepository $techDataRepository, EntityManagerInterface $manager){

        if ($request->isXmlHttpRequest()) {

            $idTechData = $request->request->get('idTechData');

            $techData = $techDataRepository->findOneById($idTechData);

            if ($presentation->getTechnicalData()->contains($techData)) {

                $presentation->removeTechnicalData($techData);
                
                $manager->remove($techData);

                $manager->persist($presentation);

                $manager->flush();
            }

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }


}
