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
     * 
     * @Route("/edit/", name="edit_text_description")
     * 
     */
    public function edit(PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {

        $this->denyAccessUnlessGranted('edit', $presentation);

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

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
                '_fragment' => 'textDescriptionDisplay',
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
