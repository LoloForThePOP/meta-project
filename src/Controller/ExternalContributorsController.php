<?php

namespace App\Controller;

use App\Entity\Persorg;
use App\Entity\PPBasic;
use App\Form\PersorgType;
use App\Service\ImageEditService;
use App\Repository\PersorgRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ExternalContributorsStructure;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ExternalContributorsStructureType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ExternalContributorsStructureRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/external-contributors")
 * 
 */
class ExternalContributorsController extends AbstractController
{

    /* The "External Contributors Creation" is managed in PPController, into function "edit presentation menu" */

    /**
     * Allow to Manage an External Contributors Structure (ECS) (ui to create; delete; insert ec into ecs)
     * 
     * @Route("/{id_ecs}", name="manage_ecs")
     * 
     */
    public function manageECS ($id_ecs, PPBasic $presentation, Request $request, EntityManagerInterface $manager, ExternalContributorsStructureRepository $ecsRepository, ImageEditService $editImageService)
    {

        $this->denyAccessUnlessGranted('edit', $presentation);

        $ecs = $ecsRepository->find($id_ecs);

        /* Rich Text Content Form */

        $ecsForm = $this->createForm(ExternalContributorsStructureType::class, $ecs);
    
        $ecsForm->handleRequest($request);

        if ($ecsForm->isSubmitted() && $ecsForm->isValid()){

            $ecs->setProject($presentation);

            $manager->persist($ecs);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
                '_fragment' => 'externalContributorsDisplay',
            ]);

        }

        $persorg = new Persorg();
        
        $persorgForm = $this->createForm(PersorgType::class, $persorg);

        $persorgForm->handleRequest($request);

        if ($persorgForm->isSubmitted() && $persorgForm->isValid()){

            $persorg->setExternalContributorsStructure($ecs);

            $manager->persist($persorg);

            $manager->flush();

            $editImageService->edit('presentation_external_contributor', $persorg->getImage());

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_ecs', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
                'id_ecs' => $id_ecs,
            ]);

        }

        return $this->render('external_contributors/manage.html.twig', [

            'ecs' => $ecs,
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
    public function addPersorg (PPBasic $presentation, $ecs, Request $request, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('edit', $presentation);

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
     * Allow to delete an ecs
     * 
     * @Route("/{id}/delete", name="ecs_delete")
     * 
     */
    public function deleteECS($slug, Request $request, ExternalContributorsStructure $ecs)
    {
        $this->denyAccessUnlessGranted('edit', $ecs->getProject());

      
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($ecs);
        $entityManager->flush();
        

        return $this->redirectToRoute('edit_presentation_menu',[
            'slug' => $slug,
        ]);
    }
    
    /**
     * Allow to remove a Persorg (with an ajax request)
     * 
     * @Route("/ajax-remove-persorg/{id_ecs}", name="ajax_remove_persorg")
     * 
     */
    public function ajaxRemovePersorg($id_ecs, ExternalContributorsStructureRepository $ecsRepository, PPBasic $presentation, Request $request, PersorgRepository $persorgRepository, EntityManagerInterface $manager){

        $this->denyAccessUnlessGranted('edit', $presentation);

        if ($request->isXmlHttpRequest()) {

            $idPersorg = $request->request->get('idPersorg');

            $persorg = $persorgRepository->findOneById($idPersorg);
            $ecs = $ecsRepository->findOneById($id_ecs);

            if ($ecs->getPersorgs()->contains($persorg)) {

                $ecs->removePersorg($persorg);
                
                $manager->remove($persorg);

                $manager->persist($presentation);

                $manager->flush();
            }
            

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }

        
    /**
     * Allow to Edit an External Contributors Structure
     * 
     * @Route("/edit/{id_ecs}", name="edit_ecs")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function editECS (PPBasic $presentation, $id_ecs, ExternalContributorsStructureRepository $ecsRepository, Request $request, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('edit', $presentation);

        $ecs = $ecsRepository->findOneById($id_ecs);

        $form = $this->createForm(ExternalContributorsStructureType::class, $ecs);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($ecs);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_ecs', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
                'id_ecs' => $id_ecs,
            ]);

        }
    
        return $this->render('external_contributors/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'id_ecs' => $id_ecs,
            'presentation' => $presentation,
        ]);
    }
        
    /**
     * Allow to Edit a Persorg
     * 
     * @Route("/edit/{id_ecs}/{id_persorg}", name="edit_persorg")
     * 
     */
    public function editPersorg (PPBasic $presentation, $id_ecs, $id_persorg, PersorgRepository $persorgRepository, Request $request, EntityManagerInterface $manager)
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
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_ecs', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
                'id_ecs' => $id_ecs,
            ]);

        }
    
        return $this->render('external_contributors/edit_persorg.html.twig', [
            'form' => $form->createView(),
            'persorg' => $persorg,
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
            'id_ecs' => $id_ecs,
        ]);
    }

 
    /**
     * Allow to modify ecs positions with an ajax request
     *
     * @Route("/ajax-reorder-ecs/", name="ajax_reorder_ecs")
     * 
    */ 
    public function ajaxReorderECS(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        $this->denyAccessUnlessGranted('edit', $presentation);

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
     * Allow to modify persorgs positions (within an ECS) with an ajax request
     *
     * @Route("/ajax-reorder-ecs-persorgs/{id_ecs}", name="ajax_reorder_ecs_persorgs")
     * 
    */ 
    public function ajaxReorderECSPersorgs($id_ecs, ExternalContributorsStructureRepository $ecsRepository, Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        $this->denyAccessUnlessGranted('edit', $presentation);

        if ($request->isXmlHttpRequest()) {

            $ecs = $ecsRepository->findOneById($id_ecs);

            $jsonPersorgsPositions = $request->request->get('jsonPersorgsPositions');

            $persorgsPositions = json_decode($jsonPersorgsPositions, true);

            foreach ($ecs->getPersorgs() as $persorg){

                $newPersorgPosition = array_search($persorg->getId(), $persorgsPositions, false);
                
                $persorg->setPosition($newPersorgPosition);

                $manager->persist($persorg);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }


}
