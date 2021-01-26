<?php

namespace App\Controller;

use App\Form\InvitePresenterByEmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/projects/{slug}/access")
 * 
 */
class RightsController extends AbstractController
{
    /**
     * @Route("/manage", name="manage_access")
     */
    public function manage(Request $request): Response
    {

        $inviteByEmailForm = $this->createForm(InvitePresenterByEmailType::class, null, array(
            // Time protection
            'antispam_time'     => true,
            'antispam_time_min' => 10,
            'antispam_time_max' => 1000,
        ));

        $inviteByEmailForm->handleRequest($request);

        if ($inviteByEmailForm->isSubmitted() && $inviteByEmailForm->isValid()){

            return $this->redirectToRoute('manage_access', [
                'slug' => $presentation->getSlug()
            ]);
        }

        return $this->render('rights/manage.html.twig', [
            'inviteByEmailForm' => $inviteByEmailForm->createView(),
        ]);




    }
}
