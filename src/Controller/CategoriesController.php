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
     */
    public function index(PPBasic $presentation, Request $request, EntityManagerInterface $manager, CategoryRepository $categoryRepository)
    {

        $this->denyAccessUnlessGranted('edit', $presentation);

        $categories = $categoryRepository->findBy([], ['position' => 'ASC']);

        $form = $this->createForm(KeywordsOnlyType::class, $presentation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($presentation);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les mots-clés du Projet ont été mis à jour"
            );

            return $this->redirectToRoute('edit_presentation_menu', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'presentation' => $presentation,
        ]);
        
    }


    /** 
     * @Route("/ajaxUpdateCategory", name="ajax_update_category") 
     * 
    */ 
    public function ajaxUpdateCategory(Request $request, PPBasic $presentation, CategoryRepository $categoryRepository, EntityManagerInterface $manager) {

        $this->denyAccessUnlessGranted('edit', $presentation);

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
