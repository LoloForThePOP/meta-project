<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\TextDescription;
use App\Form\TextDescriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/text-description")
 */
class TextDescriptionController extends AbstractController
{
    /**
     * @Route("/edit/", name="edit_text_description")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function edit(PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {

        if (!$presentation->getTextDescription()){

             $textDescription = new TextDescription();
        }

        else {
            $textDescription = $presentation->getTextDescription();
        }

        $form = $this->createForm(TextDescriptionType::class, $textDescription);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $textDescription->setPresentation($presentation);

            $manager->persist($textDescription);

            $manager->persist($presentation);

            $manager->flush();

            $this->addFlash(
                'success',
                "La description texte a bien été éditée"
            );

            return $this->redirectToRoute('edit_presentation_menu', [
                'slug' => $presentation->getSlug(),
            ]);

        }

        return $this->render(
            
            'text_description/edit.html.twig', 
            [
                'form' => $form->createView(),
                'slug' => $presentation->getSlug(),
            ]
        );
    }

    
}
