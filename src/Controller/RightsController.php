<?php

namespace App\Controller;

use App\Entity\Right;
use App\Entity\PPBasic;
use App\Entity\ContactMessage;
use App\Repository\RightRepository;
use App\Form\InvitePresenterByEmailType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ContributePresentationRequestType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/projects/{slug}/access")
 * 
 */
class RightsController extends AbstractController
{
    /**
     * @Route("/manage", name="manage_access")
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Seul le créateur de cette présentation est autorisé à accéder ici.")
     */
    public function manage(PPBasic $presentation, Request $request): Response
    {

        $inviteByEmailForm = $this->createForm(InvitePresenterByEmailType::class, null, array(
            // Time protection
            'antispam_time'     => true,
            'antispam_time_min' => 10,
            'antispam_time_max' => 1000,
        ));

        $inviteByEmailForm->handleRequest($request);

        if ($inviteByEmailForm->isSubmitted() && $inviteByEmailForm->isValid()){

            return $this->redirectToRoute('manage_access', [
                'slug' => $presentation->getSlug()
            ]);
        }

        return $this->render('rights/manage.html.twig', [
            'inviteByEmailForm' => $inviteByEmailForm->createView(),
            'presentationRights' => $presentation->getRights(),
            'accessCode' => $presentation->getAccessCode(),
            'slug' => $presentation->getSlug(),
        ]);
    }


    /**
     * 
     * Allow visitor to Request Edition Right for project presentation
     * 
     * @Route("/new_presenter_request", name="edit_presentation_request")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     */
    public function requestAccess(PPBasic $presentation, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(ContributePresentationRequestType::class, null, array(
            // Time protection
            'antispam_time'     => true,
            'antispam_time_min' => 4,
            'antispam_time_max' => 3600,));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // checking access code

            $userCode =(int)$form->get('accessCode')->getData();

            if ($userCode == $presentation->getAccessCode())
            {

                $access = new Right();

                $access ->setType('edit')
                        ->setUser($this->getUser())
                        ->setPresentation($presentation)
                        ->setStatus('candidate');
    
                $manager->persist($access);

                // we send a message to presentation creator

                $contactMessage = new ContactMessage();
                
                $contactMessage ->setCreatedAt(new \DateTime('now'))
                                ->setSenderEmail('noreply@projetdesprojets.com')
                                ->setTitle('Demande de Participation à une Présentation')
                                ->setContent('Un utilisateur demande à participer à la présentation du projet "'.$presentation->getGoal().'". Seul le créateur de la présentation peut intégrer le candidat. Pour intégrer le candidat, aller dans Paramètres, puis Gérer les accès, puis Accepter.')
                                ->setPresentation($presentation)
                                ->addReceiver($presentation->getCreator());

                $manager->persist($contactMessage);
    
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    "Votre demande a été envoyée au créateur de la présentation. Vous pourrez participer à la présentation quand son créateur aura validé votre demande."
                );

            }

            else {
                $this->addFlash(
                    'danger',
                    "Le code correct. Ce code est connu du créateur de la présentation."
                );
            }

            return $this->redirectToRoute('project_show', [
                'slug' => $presentation->getSlug(),
            ]);
        }

        return $this->render('rights/visitorRequest.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * 
     * Allow to integrate a new presentation presenter
     * 
     * @Route("/integrate_candidate/{rightId}", name="integrate_presenter_candidate")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()")
     * 
     */
    public function integrateCandidate(PPBasic $presentation, RightRepository $repo, int $rightId, EntityManagerInterface $manager)
    {

        $right = $repo->find($rightId);

        //dd($right);

        $right -> setStatus('admitted');

        $manager->persist($right);
        $manager->flush();


        return $this->redirectToRoute('manage_access', [
            'slug' => $presentation->getSlug(),
        ]);

    }


     /**
     * 
     * Allow to exclude a candidate presenter, or remove a presenter
     * 
     * @Route("/remove_right/{rightId}", name="remove_right")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()")
     * 
     */
    public function removePresenterOrCandidate(PPBasic $presentation, RightRepository $repo, int $rightId, EntityManagerInterface $manager)
    {

        $right = $repo->find($rightId);

        $manager->remove($right);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le présentateur a été retiré."
        );


        return $this->redirectToRoute('manage_access', [
            'slug' => $presentation->getSlug(),
        ]);

    }







}
