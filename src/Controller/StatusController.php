<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\PPBasic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/projects/{slug}/status")
 */
class StatusController extends AbstractController
{
    /**
     * @Route("/manage", name="manage_status")
     */
    public function manage(PPBasic $presentation, Request $request, EntityManagerInterface $manager): Response
    {
        
        return $this->render('status/manage.html.twig', [
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
        
    }

    /** 
     * @Route("/ajaxUpdateStatus", name="ajax_update_status") 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
    */ 
    public function ajaxUpdateCategory(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $newStatus = $request->request->get('projectStatus');

            if (!$presentation->getStatus()) {

                $status = new Status();

                $status->setGlobal($newStatus);

                $presentation->setStatus($status);

                $manager->persist($status);

                $manager->persist($presentation);

                $manager->flush();
            }

            else {

                $presentation->getStatus()->setGlobal($newStatus);

                $manager->persist($presentation);

                $manager->flush();
            }

            return new Response();

            /* $arrData = [
                'catId' => $categoryId,
            ];

            return new JsonResponse($arrData); */

        }

    }



}
