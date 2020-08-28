<?php

namespace App\Controller;

use App\Entity\Persorg;
use App\Entity\PPBasic;
use App\Form\PersorgType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ExternalContributorsStructure;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ExternalContributorsStructureType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ExternalContributorsStructureRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/external-contributors")
 * 
 * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
 */
class ExternalContributorsController extends AbstractController
{
    /**
     * Allow to Manage External Contributors Structure (ECS) (ui to create; delete; insert ec into ecs)
     * 
     * @Route("/", name="manage_ecs")
     * 
     */
    public function manageECS (PPBasic $presentation, Request $request, EntityManagerInterface $manager, ExternalContributorsStructureRepository $ecsRepository)
    {
        $ecs = new ExternalContributorsStructure();
        
        $ecsForm = $this->createForm(ExternalContributorsStructureType::class, $ecs);

        $ecsForm->handleRequest($request);

        $persorg = new Persorg();
        
        $persorgForm = $this->createForm(PersorgType::class, $persorg);

        $persorgForm->handleRequest($request);

        if ($ecsForm->isSubmitted() && $ecsForm->isValid()){

            $ecs->setProject($presentation);

            $manager->persist($ecs);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_ecs', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        if ($persorgForm->isSubmitted() && $persorgForm->isValid()){

            $parentECSId = $persorgForm->get('parentExternalContributorsStructure')->getData();

            $ecs = $ecsRepository->findOneById($parentECSId);

            $persorg->setExternalContributorsStructure($ecs);

            $manager->persist($persorg);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_ecs', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        return $this->render('external_contributors/manage.html.twig', [

            'ecsForm' => $ecsForm->createView(),
            'persorgForm' => $persorgForm->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
            
        ]);

    }


    /**
     * Allow to Add a Person or an Organisation into an External Contributors Structure (ECS)
     * 
     * @Route("/{id_ecs}/add-persorg", name="add_persorg")
     * 
     */
    public function addPersorg (PPBasic $presentation, $id_ecs, ExternalContributorsStructureRepository $ecsRepository, Request $request, EntityManagerInterface $manager)
    {
        $persorg = new Persorg();
        
        $persorgForm = $this->createForm(PersorgType::class, $persorg);

        $persorgForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $ecs = $ecsRepository->findOneById($id_ecs);

            $persorg->setExternalContributorsStructure($ecs);

            $manager->persist($persorg);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('add_ecs', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

    }

        
    /**
     * Allow to Edit an External Contributors Structure
     * 
     * @Route("/edit/{id_ecs}", name="edit_ecs")
     * 
     */
    public function editECS (PPBasic $presentation, $id_ecs, ExternalContributorsStructureRepository $ecsRepository, Request $request, EntityManagerInterface $manager)
    {

        $ecs = $ecsRepository->findOneById($id_ecs);

        $form = $this->createForm(ExternalContributorsStructureType::class, $ecs);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($ecs);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont bien été effectuées !"
            );

            return $this->redirectToRoute('manage_ecs', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }
    
        return $this->render('external_contributors/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }

 
    /**
     * Allow to modify ecs positions with an ajax request
     *
     * @Route("/ajax-reorder-ecs/", name="ajax_reorder_ecs")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
    */ 
    public function ajaxReorderECS(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonECSPositions = $request->request->get('jsonECSPositions');

            $ecsPositions = json_decode($jsonECSPositions, true);

            foreach ($presentation->getExternalContributorsStructures() as $ecs){

                $newECSPosition = array_search($ecs->getId(), $ecsPositions, false);
                
                $ecs->setPosition($newECSPosition);

                $manager->persist($ecs);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }

    
    /**
     * Allow to remove an ecs (with an ajax request)
     * 
     * @Route("/ajax-remove-ecs/", name="ajax_remove_ecs")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function ajaxRemoveECS(PPBasic $presentation, Request $request, ExternalContributorsStructureRepository $ecsRepository, EntityManagerInterface $manager){

        if ($request->isXmlHttpRequest()) {

            $idECS = $request->request->get('idECS');

            $ecs = $ecsRepository->findOneById($idECS);

            if ($presentation->getExternalContributorsStructures()->contains($ecs)) {

                $presentation->removeExternalContributorsStructure($ecs);
                
                $manager->remove($ecs);

                $manager->persist($presentation);

                $manager->flush();
            }

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }


}
