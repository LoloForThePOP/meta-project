<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\Website;
use App\Form\WebsiteType;
use App\Form\WebsiteCollectionType;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/websites")
 * 
 * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
 * 
 */
class WebsiteController extends AbstractController
{

    /**
     * @Route("/", name="websites_index")
     */
    public function index (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {
        $website = new Website ();

        $form = $this->createForm(WebsiteType::class, $website);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $website->setPresentation($presentation);

            $manager->persist($website);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('websites_index', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }
    
        return $this->render('websites/index.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }

    /**
     * @Route("/edit/{idWebsite}", name="edit_website")
     */
    public function edit (PPBasic $presentation, $idWebsite, WebsiteRepository $websiteRepo, Request $request, EntityManagerInterface $manager)
    {

        $website = $websiteRepo->findOneById($idWebsite);

        $form = $this->createForm(WebsiteType::class, $website);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $website->setPresentation($presentation);

            $manager->persist($website);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont bien été effectuées !"
            );

            return $this->redirectToRoute('websites_index', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }
    
        return $this->render('websites/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }

    
    /**
     * Allow to modify Website positions with an ajax request
     *
     * @Route("/ajax-reorder-websites/", name="ajax_reorder_websites")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
    */ 
    public function ajaxReorderWebsites(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonWebsitesPosition = $request->request->get('jsonWebsitesPosition');

            $websitesPosition = json_decode($jsonWebsitesPosition,true);

            foreach ($presentation->getWebsites() as $website){

                $newWebsitePosition = array_search($website->getId(), $websitesPosition, false);
                
                $website->setPosition($newWebsitePosition);

                $manager->persist($website);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }

    
    /**
     * Allow to remove a Website (with an ajax request)
     * 
     * @Route("/ajax-remove-website/", name="ajax_remove_website")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function ajaxRemoveWebsite(PPBasic $presentation, Request $request, WebsiteRepository $websiteRepository, EntityManagerInterface $manager){

        if ($request->isXmlHttpRequest()) {

            $idWebsite = $request->request->get('idWebsite');

            $website = $websiteRepository->findOneById($idWebsite);

            if ($presentation->getWebsites()->contains($website)) {

                $presentation->removeWebsite($website);
                
                $manager->remove($website);

                $manager->persist($presentation);

                $manager->flush();
            }

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }



}
