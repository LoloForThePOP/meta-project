<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\Document;
use App\Form\DocumentType;
use App\Entity\PPMajorLogs;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/documents")
 */

class DocumentController extends AbstractController
{

    
    /**
     * user interface that allow to display, remove, reorder, add new Document
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     * @Route("/", name="manage_documents")
     */
    public function manage (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {
        $document = new Document();
        
        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $document->setPresentation($presentation);

            $manager->persist($document);

            $manager->flush();

            $idDocument=$document->getId();

            PPMajorLogs::updateLogs($presentation, 'document', 'new', $idDocument, $manager);

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_documents', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        return $this->render('document/manage.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);

    }

    /**
     * Allow to Edit a Document
     * 
     * @Route("/edit/{idDocument}", name="edit_document")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function edit (PPBasic $presentation, $idDocument, DocumentRepository $documentRepo, Request $request, EntityManagerInterface $manager)
    {

        $document = $documentRepo->findOneById($idDocument);

        $form = $this->createForm(DocumentType::class, $document)->remove('file');
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $document->setPresentation($presentation);

            $manager->persist($document);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont bien été effectuées !"
            );

            return $this->redirectToRoute('manage_documents', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }
    
        return $this->render('document/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }



    /**
     * Allow to remove a Document (with an ajax request)
     * 
     * @Route("/ajax-remove-document/", name="ajax_remove_document")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function ajaxRemoveDocument(PPBasic $presentation, Request $request, DocumentRepository $documentRepository, EntityManagerInterface $manager){

        if ($request->isXmlHttpRequest()) {

            $idDocument = $request->request->get('idDocument');

            $document = $documentRepository->findOneById($idDocument);

            if ($presentation->getDocuments()->contains($document)) {

                $presentation->removeDocument($document);
                
                $manager->remove($document);

                $manager->persist($presentation);

                $manager->flush();
            }

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }

    
    /**
     * Allow to reorder documents positions with an ajax request
     *
     * @Route("/ajax-reorder-documents/", name="ajax_reorder_documents")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
    */ 
    public function ajaxReorderDocuments(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonDocumentsPosition = $request->request->get('jsonDocumentsPosition');

            $documentsPosition = json_decode($jsonDocumentsPosition,true);

            foreach ($presentation->getDocuments() as $document){

                $newDocumentPosition = array_search($document->getId(), $documentsPosition, false);
                
                $document->setPosition($newDocumentPosition);

                $manager->persist($document);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }

    
    /**
     * show new document details in user notification page
     * 
     * @Route("/document/ajax-show-embed", name="show_embed_document")
     * 
     */
    public function showEmbed(Request $request, DocumentRepository $documentRepository)
    {
        
        if ($request->isXmlHttpRequest()) {

            //get selected news

            $idDocument = $request->request->get('idEntity');

            $document = $documentRepository->findOneById($idDocument);

            $dataResponse = [

                'html' => $this->renderView(
                    
                    'document/show_embed.html.twig', 

                    [
                        'document' => $document,
                    ]
                ),
            ];

            //dump($dataResponse);

            return new JsonResponse($dataResponse);

        
        }

    }



}
