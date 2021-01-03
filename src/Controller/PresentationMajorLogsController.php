<?php

namespace App\Controller;

use App\Entity\PPBasic;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** 
 * 
 * @Route("/projects/{slug}/major-logs/")
 * 
 */
class PresentationMajorLogsController extends AbstractController
{
    /**
     * @Route("show", name="show_major_logs")
     */
    public function show(PPBasic $presentation): Response
    {

        $majorLogs=$presentation->getPresentationMajorLogs();

        $logsUpdatedAt=$majorLogs->getUpdatedAt();
        $logsList=$majorLogs->getMajorLogs();

        return $this->render('presentation_major_logs/index.html.twig', [

            'logsLastUpdate' => $logsUpdatedAt,
            'logsList' => $logsList,
        ]);

    }
    
}
