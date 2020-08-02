<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\QuestionAnswer;
use App\Form\QuestionAnswerType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\QuestionAnswerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/projects/{slug}/question_answer")
 * 
 * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
 * 
 */

class QuestionAnswerController extends AbstractController
{


    /**
     * user interface that allow to display, remove, reorder, add new q&a
     * 
     * @Route("/", name="qa_manage")
     */
    public function manage (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {
        $qa = new QuestionAnswer ();
        
        $form = $this->createForm(QuestionAnswerType::class, $qa);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $qa->setPresentation($presentation);

            $manager->persist($qa);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('qa_manage', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        return $this->render('question_answer/manage.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);

    }

    
    /**
     * Allow to remove a Q&A (with an ajax request)
     * 
     * @Route("/ajax-remove-qa/", name="ajax_remove_qa")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function ajaxRemoveQA(PPBasic $presentation, Request $request, QuestionAnswerRepository $qaRepository, EntityManagerInterface $manager){

        if ($request->isXmlHttpRequest()) {

            $idQA = $request->request->get('idQA');

            $qa = $qaRepository->findOneById($idQA);

            if ($presentation->getQuestionAnswers()->contains($qa)) {

                $presentation->removeQuestionAnswer($qa);
                
                $manager->remove($qa);

                $manager->persist($presentation);

                $manager->flush();
            }

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }
    
    /**
     * Allow to Edit a Q&A
     * 
     * @Route("/edit/{idQA}", name="edit_qa")
     */
    public function edit (PPBasic $presentation, $idQA, QuestionAnswerRepository $qaRepo, Request $request, EntityManagerInterface $manager)
    {

        $qa = $qaRepo->findOneById($idQA);

        $form = $this->createForm(QuestionAnswerType::class, $qa);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $qa->setPresentation($presentation);

            $manager->persist($qa);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont bien été effectuées !"
            );

            return $this->redirectToRoute('qa_manage', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }
    
        return $this->render('question_answer/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }
    
    /**
     * Allow to modify Q&A positions with an ajax request
     *
     * @Route("/ajax-reorder-qa/", name="ajax_reorder_qas")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
    */ 
    public function ajaxReorderQAs(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonQAsPosition = $request->request->get('jsonQAsPosition');

            $qasPosition = json_decode($jsonQAsPosition,true);

            foreach ($presentation->getQuestionAnswers() as $qa){

                $newQAPosition = array_search($qa->getId(), $qasPosition, false);
                
                $qa->setPosition($newQAPosition);

                $manager->persist($qa);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

    }



}
