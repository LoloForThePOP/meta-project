<?php

namespace App\Entity;

use App\Entity\Persorg;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields={"email"},
 * message="Un utilisateur s'est déjà inscrit avec cette adresse email."
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner un email valide")
     */
    private $email;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=4, minMessage="Votre Mot de Passe doit faire minimum {{ limit }} caractères")
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userSlug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PPBasic", mappedBy="creator")
     */
    private $presentations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ContactMessage", mappedBy="receivers")
     */
    private $contactMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PGroup", mappedBy="creator")
     */
    private $createdPGroups;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PGroup", mappedBy="masters")
     */
    private $pGroupsMaster;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="user")
     */
    private $reports;

    /**
     * Is the User allowed or banished on the website
     * 
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAllowed;

    /**
     * comments over a user banishment
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    private $isAllowedComment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;


    /**
     * when user forget his password
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetPassToken;

    /**
     * user public profile
     * 
     * @ORM\OneToOne(targetEntity=Persorg::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private $persorg;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="creator")
     */
    private $news;

    /**
     * @ORM\OneToMany(targetEntity=UserFollows::class, mappedBy="user")
     */
    private $userFollows;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastNotificationsConnection;

    /**
     * @ORM\OneToMany(targetEntity=Right::class, mappedBy="user")
     */
    private $rights;

    /**
     * @ORM\OneToMany(targetEntity=MessageConsultation::class, mappedBy="user", orphanRemoval=true)
     */
    private $getConsultedMessages;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastNewCommentsConnection;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registrationToken;

    /**
     * @ORM\Column(type="json", nullable=true)
     * 
     * List of properties :
     * 
     * 'notificationsAcceptation' : boolean : does user accept regular emails about projects he follows
     * 
     */
    private $emailsReception = [];





    public function __construct()
    {
        
        // we create a public profile for user
        $userPersorg = new Persorg();
        $userPersorg->setName('anonyme');
        $this->setPersorg($userPersorg);


        // default user emails reception and preferences
        $this->setEmailsReception(
            [
                'notificationsAcceptation' => true,
            ]
        
        );

        // new user is allowed on website by default
        $this->isAllowed = true;

        $this->setLastNotificationsConnection(new \DateTime('now'));
        $this->setLastNewCommentsConnection(new \DateTime('now'));
        
        $this->createdAt = new \DateTime('now');
        $this->presentations = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->contactMessages = new ArrayCollection();
        $this->pGroups = new ArrayCollection();
        $this->pGroupsMaster = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->news = new ArrayCollection();
        $this->userFollows = new ArrayCollection();
        $this->rights = new ArrayCollection();
        $this->getConsultedMessages = new ArrayCollection();
        
    }
  
    public function __toString()
    {
        return (string) $this->getPersorg()->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }


    public function getUserSlug(): ?string
    {
        return $this->userSlug;
    }

    public function setUserSlug(?string $userSlug): self
    {
        $this->userSlug = $userSlug;

        return $this;
    }

    /**
     * @return Collection|PPBasic[]
     */
    public function getPresentations(): Collection
    {
        return $this->presentations;
    }

    public function addPresentation(PPBasic $presentation): self
    {
        if (!$this->presentations->contains($presentation)) {
            $this->presentations[] = $presentation;
            $presentation->setCreator($this);
        }

        return $this;
    }

    public function removePresentation(PPBasic $presentation): self
    {
        if ($this->presentations->contains($presentation)) {
            $this->presentations->removeElement($presentation);
            // set the owning side to null (unless already changed)
            if ($presentation->getCreator() === $this) {
                $presentation->setCreator(null);
            }
        }

        return $this;
    }

    
    public function getRoles(){

        
        $roles=$this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[]='ROLE_USER';


        return $roles;
        
    }

    public function getPassword(){
        return $this->hash;
    }

    public function getSalt(){}

    public function getUsername(){
        return $this->email;
    }

    public function eraseCredentials(){}

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|ContactMessage[]
     */
    public function getContactMessages(): Collection
    {
        return $this->contactMessages;
    }

    public function addContactMessages(ContactMessage $contactMessages): self
    {
        if (!$this->contactMessages->contains($contactMessages)) {
            $this->contactMessages[] = $contactMessages;
            $contactMessages->addReceiver($this);
        }

        return $this;
    }

    
    /**
     * Allow to get user unread messages
     *
     * @return array
     */
    public function getUnreadMessages()
    {
        $unreadMessages = [];

        foreach ($this->getContactMessages() as $item) {

            if ($item->getHasBeenConsulted() == false) { 

                $unreadMessages[] = $item;

            }
        }

        return $unreadMessages;

    }




    public function countUnreadMessages()
    {
        $count = 0;

        foreach ($this->getContactMessages() as $item) {

            if ($item->getHasBeenConsulted() == false) { 
                $count++;
            }
        }

        return $count;
    }

    public function removeContactMessages(ContactMessage $contactMessages): self
    {
        if ($this->contactMessages->contains($contactMessages)) {
            $this->contactMessages->removeElement($contactMessages);
            $contactMessages->removeReceiver($this);
        }

        return $this;
    }

    /**
     * @return Collection|PGroup[]
     */
    public function getCreatedPGroups(): Collection
    {
        return $this->createdPGroups;
    }

    public function addCreatedPGroup(PGroup $pGroup): self
    {
        if (!$this->createdPGroups->contains($pGroup)) {
            $this->createdPGroups[] = $pGroup;
            $createdPGroup->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedPGroup(PGroup $pGroup): self
    {
        if ($this->createdPGroups->contains($pGroup)) {
            $this->createdPGroups->removeElement($pGroup);
            // set the owning side to null (unless already changed)
            if ($pGroup->getCreator() === $this) {
                $pGroup->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PGroup[]
     */
    public function getPGroupsMaster(): Collection
    {
        return $this->pGroupsMaster;
    }

    public function addPGroupsMaster(PGroup $pGroupsMaster): self
    {
        if (!$this->pGroupsMaster->contains($pGroupsMaster)) {
            $this->pGroupsMaster[] = $pGroupsMaster;
            $pGroupsMaster->addMaster($this);
        }

        return $this;
    }

    public function removePGroupsMaster(PGroup $pGroupsMaster): self
    {
        if ($this->pGroupsMaster->contains($pGroupsMaster)) {
            $this->pGroupsMaster->removeElement($pGroupsMaster);
            $pGroupsMaster->removeMaster($this);
        }

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setUser($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->contains($report)) {
            $this->reports->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getUser() === $this) {
                $report->setUser(null);
            }
        }

        return $this;
    }

    public function getIsAllowed(): ?bool
    {
        return $this->isAllowed;
    }

    public function setIsAllowed(?bool $isAllowed): self
    {
        $this->isAllowed = $isAllowed;

        return $this;
    }

    public function getIsAllowedComment(): ?string
    {
        return $this->isAllowedComment;
    }

    public function setIsAllowedComment(?string $isAllowedComment): self
    {
        $this->isAllowedComment = $isAllowedComment;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    public function getResetPassToken(): ?string
    {
        return $this->resetPassToken;
    }

    public function setResetPassToken(?string $resetPassToken): self
    {
        $this->resetPassToken = $resetPassToken;

        return $this;
    }

    public function getPersorg(): ?Persorg
    {
        return $this->persorg;
    }

    public function setPersorg(?Persorg $persorg): self
    {
        $this->persorg = $persorg;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Allow to get Comments related to user project presentations
     *
     * @param \DateTimeInterface $thresholdDate
     * @return void
     */
    public function getCommentsSinceDate(\DateTimeInterface $thresholdDate)
    {
        $returnedComments = [];

        //we investigate comments from project presentations user can edit

        $projectPresentations = $this->getAccessedPresentationsByRightType('edit');

        foreach ($projectPresentations as $projectPresentation) {

            $comments = $projectPresentation->getComments();

            foreach ($comments as $comment) {

                if ($comment->getCreatedAt() > $thresholdDate && $comment->getUser() !== $this) {
                    $returnedComments[] = $comment;
                }

            }
        }

        return $returnedComments;

    }


    /**
     * Allow to count new comments since specified date
     * Comments from this (user) ARE NOT taken in account
     *
     * @param \DateTimeInterface $lastLoginDate
     * @return int
     */
    public function countCommentsSinceDate(\DateTimeInterface $thresholdDate){

        return count($this->getCommentsSinceDate($thresholdDate));
    }

    /**
     * @return Collection|News[]
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->setCreator($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->removeElement($news)) {
            // set the owning side to null (unless already changed)
            if ($news->getCreator() === $this) {
                $news->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserFollows[]
     */
    public function getUserFollows(): Collection
    {
        return $this->userFollows;
    }

    public function addUserFollow(UserFollows $userFollow): self
    {
        if (!$this->userFollows->contains($userFollow)) {
            $this->userFollows[] = $userFollow;
            $userFollow->setUser($this);
        }

        return $this;
    }

    public function removeUserFollow(UserFollows $userFollow): self
    {
        if ($this->userFollows->removeElement($userFollow)) {
            // set the owning side to null (unless already changed)
            if ($userFollow->getUser() === $this) {
                $userFollow->setUser(null);
            }
        }

        return $this;
    }


    /**
     * Allow to know whether user follow a presentation or not
     *
     * @param [type] $object
     * @return boolean
     */
    public function isFollowerOf($object): bool
    {

        $userFollows = $this->getUserFollows();
        
        if ($object instanceof PPBasic) {

            foreach ($userFollows as $userFollow) {
                
                if ($userFollow->getPresentation() == $object) {

                    return true;

                }
            }
        }

        return false;
    }

    public function getLastNotificationsConnection(): ?\DateTimeInterface
    {
        return $this->lastNotificationsConnection;
    }

    public function setLastNotificationsConnection(?\DateTimeInterface $lastNotificationsConnection): self
    {
        $this->lastNotificationsConnection = $lastNotificationsConnection;

        return $this;
    }

    
    /**
     * 
     * Allow to count user notifications
     * 
     */
    public function countNotifications() : int {

        $countNotifications = 0;

        //getting user last time user accessed notification page
        $userLastConnectionDate= $this->getLastNotificationsConnection();

        $userFollows = $this->getUserFollows();

        foreach ($userFollows as $followRow) {

            $currentPresentation = $followRow->getPresentation();
  
            $presentationMajorLogs = $currentPresentation->getMajorLogs();
            
            if ($presentationMajorLogs !== null) {

                $ppLastMajorUpdateDate = $presentationMajorLogs->getUpdatedAt();

                if ($userLastConnectionDate < $ppLastMajorUpdateDate) {

                    //dump('on détecte une modification ultérieure');
                    //dump('on détecte que la présentation a été modifiée par quelqun dautre');

                    $events = $presentationMajorLogs->getLogs();

                    foreach ($events as $event) {

                        //we check if this modification has been done after his last connection, in that case we have to notify him

                        if ($userLastConnectionDate->getTimestamp() < strtotime($event['date']['date'])) {

                            //dump('on détecte que la modification est ultérieure à la dernière connection sur la page de notification');

                            //if user has not done current modification by himself

                            if ($event['creatorId'] !== $this->getId())
                            {

                                $countNotifications++;
        
                            }
                            
                        }
                        
                    }
                
                }
                
            }

        }

        return $countNotifications;

    }

    
    /**
     * Allow to update last time user connected to notification page
     *
     * @return void
     */
    public function updateLastNotificationsConsultationDate($entityManager){

        //lastConnectionDateUpdate
        $this->setLastNotificationsConnection(new \DateTime('now'));

        $entityManager->persist($this);
        $entityManager->flush();

    }

    /**
     * @return Collection|Right[]
     */
    public function getRights(): Collection
    {
        return $this->rights;
    }

    public function addRight(Right $right): self
    {
        if (!$this->rights->contains($right)) {
            $this->rights[] = $right;
            $right->setUser($this);
        }

        return $this;
    }

    public function removeRight(Right $right): self
    {
        if ($this->rights->removeElement($right)) {
            // set the owning side to null (unless already changed)
            if ($right->getUser() === $this) {
                $right->setUser(null);
            }
        }

        return $this;
    }


 

    /**
     * As its name suggest
     *
     * @param mixed $rightTypes Either a string name or an array with string values
     * 
     * @return Collection|PPBasic[]
     */
    public function getAccessedPresentationsByRightType($rightTypes){

        $accessedPresentations=[];

        // by default user has all access over presentations he created

        foreach ($this->getPresentations() as $presentation) {

            $accessedPresentations[]=$presentation;
        }

        //now we parse presentations over which he has access

        $rights = $this->getRights();

        if (!is_array($rightTypes)) {
            $rightTypes = array($rightTypes);
        }

        foreach ($rightTypes as $rightType) {
            foreach ($rights as $right) {

                if ($right->getType() == $rightType and $right->getStatus()=='admitted') {
                   
                    $accessedPresentations[] = $right->getPresentation();
    
                }
            }
        }

        

        return $accessedPresentations;

    }

    /**
     * @return Collection|MessageConsultation[]
     */
    public function getGetConsultedMessages(): Collection
    {
        return $this->getConsultedMessages;
    }

    public function addGetConsultedMessage(MessageConsultation $getConsultedMessage): self
    {
        if (!$this->getConsultedMessages->contains($getConsultedMessage)) {
            $this->getConsultedMessages[] = $getConsultedMessage;
            $getConsultedMessage->setUser($this);
        }

        return $this;
    }

    public function removeGetConsultedMessage(MessageConsultation $getConsultedMessage): self
    {
        if ($this->getConsultedMessages->removeElement($getConsultedMessage)) {
            // set the owning side to null (unless already changed)
            if ($getConsultedMessage->getUser() === $this) {
                $getConsultedMessage->setUser(null);
            }
        }

        return $this;
    }

    public function getLastLoginDate(): ?\DateTimeInterface
    {
        return $this->lastLoginDate;
    }

    public function setLastLoginDate(?\DateTimeInterface $lastLoginDate): self
    {
        $this->lastLoginDate = $lastLoginDate;

        return $this;
    }

    public function getLastNewCommentsConnection(): ?\DateTimeInterface
    {
        return $this->lastNewCommentsConnection;
    }

    public function setLastNewCommentsConnection(?\DateTimeInterface $lastNewCommentsConnection): self
    {
        $this->lastNewCommentsConnection = $lastNewCommentsConnection;

        return $this;
    }

    public function getRegistrationToken(): ?string
    {
        return $this->registrationToken;
    }

    public function setRegistrationToken(?string $registrationToken): self
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

    public function getEmailsReception(): ?array
    {
        return $this->emailsReception;
    }

    public function setEmailsReception(?array $emailsReception): self
    {
        $this->emailsReception = $emailsReception;

        return $this;
    }



    /**
     * Allow to update user emails reception data and preferences
     * List of properties : see emailsReception property
     */

    public function updateEmailsReception($property, $value)
    {

        $emailsReception = $this->getEmailsReception();

        $emailsReception[$property] = $value;

        $this->setEmailsReception($emailsReception);


        return $this;

    }



  
}
