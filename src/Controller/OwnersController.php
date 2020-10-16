<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Entity\Persorg;
use App\Entity\PPBasic;
use App\Form\PersorgType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/owners")
 * 
 * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
 */
class OwnersController extends AbstractController
{

    /**
     * @Route("/", name="manage_owners")
     * 
     */
    public function manage (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {
        $owner = new Owner();
        
        $persorg = new Persorg();
        
        $persorgForm = $this->createForm(PersorgType::class, $persorg);

        $persorgForm->handleRequest($request);
        
        if ($persorgForm->isSubmitted() && $persorgForm->isValid()){

            $owner  ->setProject($presentation)
                    ->setPersorg($persorg);

            $manager->persist($owner);

            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont été effectuées !"
            );

            return $this->redirectToRoute('manage_owners', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
            ]);

        }

        return $this->render('owners/manage.html.twig', [

            'persorgForm' => $persorgForm->createView(),
            'presentation' => $presentation,
            'slug' => $presentation->getSlug(),
            
        ]);

    }










}
