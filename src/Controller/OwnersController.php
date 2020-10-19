<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Entity\Persorg;
use App\Entity\PPBasic;
use App\Form\PersorgType;
use App\Repository\OwnerRepository;
use App\Repository\PersorgRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/owners")
 * 
 * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
 */
class OwnersController extends AbstractController
{

    /**
     * @Route("/", name="manage_owners")
     * 
     */
    public function manage (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {
        $owner = new Owner();
        
        $persorg = new Persorg();
        
        $persorgForm = $this->createForm(PersorgType::class, $persorg);

        $persorgForm->handleRequest($request);
        
        if ($persorgForm->isSubmitted() && $persorgForm->isValid()){

            $owner  ->setProject($presentation)
                    ->setPersorg($persorg);

            $manager->persist($owner);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_owners', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        return $this->render('owners/manage.html.twig', [

            'persorgForm' => $persorgForm->createView(),
            'presentation' => $presentation,
            'slug' => $presentation->getSlug(),
            
        ]);

    }


    /**
     * Allow to Edit a Persorg
     * 
     * @Route("/edit/{id_persorg}", name="edit_owner_persorg")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function editPersorg (PPBasic $presentation, $id_persorg, PersorgRepository $persorgRepository, Request $request, EntityManagerInterface $manager)
    {
   
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

            return $this->redirectToRoute('manage_owners', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }
    
        return $this->render('/persorgs/edit.html.twig', [
            'form' => $form->createView(),
            'persorg' => $persorg,
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
            'controller_name' => 'owners',
        ]);
    }

 
    /**
     * Allow to modify Owners positions with an ajax request
     *
     * @Route("/ajax-reorder-owners/", name="ajax_reorder_owners")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
    */ 
    public function ajaxReorderOwners(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonOwnersPositions = $request->request->get('jsonOwnersPositions');

            $ownersPositions = json_decode($jsonOwnersPositions,true);

            foreach ($presentation->getOwners() as $owner){

                $newOwnerPosition = array_search($owner->getId(), $ownersPositions, false);
                
                $owner->setPosition($newOwnerPosition);

                $manager->persist($owner);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }


    /**
     * Allow to remove a Project Owner (with an ajax request)
     * 
     * @Route("/ajax-remove-owner/", name="ajax_remove_owner")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function ajaxRemoveOwner(PPBasic $presentation, Request $request, OwnerRepository $ownersRepository, EntityManagerInterface $manager){

        if ($request->isXmlHttpRequest()) {

            $idOwner = $request->request->get('idOwner');

            $owner = $ownersRepository->findOneById($idOwner);

            if ($presentation->getOwners()->contains($owner)) {

                $presentation->removeOwner($owner);
                
                $manager->remove($owner);

                $manager->persist($presentation);

                $manager->flush();
            }

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }


}
