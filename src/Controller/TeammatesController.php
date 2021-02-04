<?php

namespace App\Controller;

use App\Entity\Persorg;
use App\Entity\PPBasic;
use App\Entity\Teammate;
use App\Form\PersorgType;
use App\Entity\PPMajorLogs;
use App\Form\TeammatesByTextType;
use App\Service\ImageEditService;
use App\Repository\PersorgRepository;
use App\Repository\TeammateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/teammates")
 * 
 * 
 */
class TeammatesController extends AbstractController
{
    /**
     * 
     * @Route("/", name="manage_teammates")
     * 
     */
    public function manage (PPBasic $presentation, Request $request, EntityManagerInterface $manager, ImageEditService $editImageService)
    {
        $this->denyAccessUnlessGranted('edit', $presentation);

        $teammate = new Teammate();
         
        $persorg = new Persorg();
        
        $persorgForm = $this->createForm(PersorgType::class, $persorg);

        $persorgForm->handleRequest($request);
        
        if ($persorgForm->isSubmitted() && $persorgForm->isValid()){

            $teammate   ->setProject($presentation)
                        ->setPersorg($persorg);

            $manager->persist($teammate);

            $manager->flush();

            $idTeammate=$teammate->getId();

            $editImageService->edit('presentation_teammate', $teammate->getPersorg()->getImage());

            PPMajorLogs::updateLogs($presentation, 'teammate', 'new', $idTeammate, $manager);

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_teammates', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }


        $teammatesByTextForm = $this->createForm(TeammatesByTextType::class, $presentation);
        
        $teammatesByTextForm->handleRequest($request);
        
        if ($teammatesByTextForm->isSubmitted() && $teammatesByTextForm->isValid()){

            $manager->persist($presentation);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('edit_presentation_menu', [
                'slug' => $presentation->getSlug(),
            ]);

        }


        return $this->render('teammates/manage.html.twig', [

            'persorgForm' => $persorgForm->createView(),
            'teammatesByTextForm' => $teammatesByTextForm->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
            
        ]);

    }

        
    /**
     * Allow to Edit a Teammate
     * 
     * @Route("/edit/{id_persorg}", name="edit_teammate_persorg")
     * 
     */
    public function edit (PPBasic $presentation, $id_persorg, PersorgRepository $persorgRepository, Request $request, EntityManagerInterface $manager)
    {

        $this->denyAccessUnlessGranted('edit', $presentation);

        $persorg = $persorgRepository->findOneById($id_persorg);

        $form = $this->createForm(PersorgType::class, $persorg);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($persorg);

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
    
        return $this->render('persorgs/edit.html.twig', [
            'form' => $form->createView(),
            'persorg' => $persorg,
            'controller_name' => 'teammates',
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }

 
    /**
     * Allow to modify Teammates positions with an ajax request
     *
     * @Route("/ajax-reorder-teammates/", name="ajax_reorder_teammates")
     * 
    */ 
    public function ajaxReorderTeammates(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        $this->denyAccessUnlessGranted('edit', $presentation);

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
     */
    public function ajaxRemoveTeammate(PPBasic $presentation, Request $request, TeammateRepository $teammateRepository, EntityManagerInterface $manager){

        $this->denyAccessUnlessGranted('edit', $presentation);


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

    
    /**
     * @Route("/teammate/ajax-show-embed", name="show_embed_teammate")
     * 
     */
    public function showEmbed(Request $request, TeammateRepository $teammateRepository)
    {
        
        if ($request->isXmlHttpRequest()) {

            //get selected news

            $idTeammate = $request->request->get('idEntity');

            $teammate = $teammateRepository->findOneById($idTeammate);

            $dataResponse = [

                'html' => $this->renderView(
                    
                    'teammates/show_embed_teammate.html.twig', 

                    [
                        'persorg' => $teammate->getPersorg(),
                    ]
                ),
            ];

            //dump($dataResponse);

            return new JsonResponse($dataResponse);

        
        }

    }


}
