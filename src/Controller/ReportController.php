<?php

namespace App\Controller;

use App\Entity\Report;
use App\Form\ReportType;
use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/report")
 */
class ReportController extends AbstractController
{
    /**
     * @Route("/new/{context}", name="report_new", methods={"GET","POST"})
     */
    public function new(Request $request, $context=null): Response
    {
        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $report
                ->setContext($context)
                ->setUser($this->getUser());

            $entityManager->persist($report);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Votre message a été envoyé. Merci d'avoir envoyé ce message."
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('report/new.html.twig', [
            'report' => $report,
            'form' => $form->createView(),
        ]);
    }

}
