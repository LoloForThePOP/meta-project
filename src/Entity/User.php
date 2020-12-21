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



    public function __construct()
    {
        
        // we create a public profile for user
        $userPersorg = new Persorg();
        $userPersorg->setName('anonyme');
        $this->setPersorg($userPersorg);

        // new user is allowed on website by default
        $this->isAllowed = true;
        
        $this->createdAt = new \DateTime('now');
        $this->presentations = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->contactMessages = new ArrayCollection();
        $this->pGroups = new ArrayCollection();
        $this->pGroupsMaster = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->comments = new ArrayCollection();                
        
    }
  
    public function __toString()
    {
        return (string) $this->name;
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

  
}
