<?php

namespace App\Controller;

use App\Form\PollType;
use App\Entity\PPBasic;
use App\Entity\Website;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/projects/{slug}/polls")
 */
class PollController extends AbstractController
{
    /**
     * @Route("/manage/", name="manage_polls")
     */
    public function manage(PPBasic $presentation, Request $request, EntityManagerInterface $manager): Response
    {

        $this->denyAccessUnlessGranted('edit', $presentation);

        $pollWebsite = new Website ();
        $pollWebsite->setType('poll');

        $addPollForm = $this->createForm(PollType::class,  $pollWebsite);
        
        $addPollForm->handleRequest($request);

        if ($addPollForm->isSubmitted() && $addPollForm->isValid()){

            $pollWebsite->setPresentation($presentation);

            $manager->persist($pollWebsite);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
                '_fragment' => 'pollsDisplay',
                ]);

        }
            
        return $this->render('polls/manage.html.twig', [
            'presentation' => $presentation,
            'polls' => $presentation->getWebsitesByType('poll'),
            'addPollForm' => $addPollForm->createView(),
        ]);

    }
}
