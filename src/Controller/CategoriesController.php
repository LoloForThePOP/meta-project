<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\Category;
use App\Form\KeywordsOnlyType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/categories")
 */
class CategoriesController extends AbstractController
{
    /**
     * Allow to Display Edit Project Categories (with an ajax function bellow), and Display / Manage an Edit Keywords a Form
     * 
     * @Route("/", name="index_categories")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function index(PPBasic $presentation, Request $request, EntityManagerInterface $manager, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        $form = $this->createForm(KeywordsOnlyType::class, $presentation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($presentation);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les Mots Clés du Projet ont étés mis à jour"
            );

        }

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'presentation' => $presentation,
        ]);
        
    }


    /** 
     * @Route("/ajaxUpdateCategory", name="ajax_update_category") 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
    */ 
    public function ajaxUpdateCategory(Request $request, PPBasic $presentation, CategoryRepository $categoryRepository, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $categoryId = $request->request->get('catId');

            $category = $categoryRepository->findOneById($categoryId);
                
            $presentationCategories = $presentation->getCategories();

            if (!$presentationCategories->contains($category)) {
                $presentation->addCategories($category);
            }else{
                $presentation->removeCategories($category);
            }

            $manager->persist($presentation);
            $manager->flush();

            return new Response();

            /* $arrData = [
                'catId' => $categoryId,
            ];

            return new JsonResponse($arrData); */

        }

    }

}
