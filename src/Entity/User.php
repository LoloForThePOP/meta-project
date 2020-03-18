<?php

namespace App\Entity;

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
 * message="Un utilisateur s'est déjà inscrit avec cette adresse email. Si cette adresse email est bien la vôtre, vous êtes déjà inscrit sur le site. Si vous ne vous souvenez plus de votre Mot de Passe, Dirigez vous vers la section Mot de Passe Oublié."
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
    private $image;

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


    public function __construct()
    {
        $this->presentations = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

        return ['ROLE_USER'];
    }

    public function getPassword(){
        return $this->hash;
    }

    public function getSalt(){}

    public function getUsername(){
        return $this->email;
    }

    public function eraseCredentials(){}

}
