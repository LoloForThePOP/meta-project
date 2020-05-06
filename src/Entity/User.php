<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields={"email"},
 * message="Un utilisateur s'est déjà inscrit avec cette adresse email. Si cette adresse email est bien la vôtre, vous êtes déjà inscrit sur le site. Si vous ne vous souvenez plus de votre mot de passe, dirigez vous vers la section mot de passe oublié."
 * )
 * @UniqueEntity(
 * fields={"name"},
 * message="Un utilisateur utilise déjà ce nom. Veuillez Utiliser un autre nom"
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner un email valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=4, minMessage="Votre Mot de Passe doit faire minimum {{ limit }} caractères")
     */
    private $hash;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userSlug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PPBasic", mappedBy="creator", orphanRemoval=true)
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


    public function __construct()
    {
        $this->presentations = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->contactMessages = new ArrayCollection();
        $this->pGroups = new ArrayCollection();
        $this->pGroupsMaster = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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


}
