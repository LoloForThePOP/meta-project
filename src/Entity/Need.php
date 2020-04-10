<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NeedRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Need
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Le Titre du Besoin doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le Titre du Besoin doit faire au maximum {{ limit }} caractères",
     * )
     */
    private $title;


    /**
     * The project presentation whereby the need is connected
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\PPBasic", inversedBy="needs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $presentation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * the need can be tagged as a top priority needed ressource
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $priority;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * Exemples of need types : a material; a service / skill; a money amount
     * 
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $type;

    /**
     * Presenters can ordonate their need (not used for the moment)
     * 
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    /**
     * Is the need payed or not
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paidService;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Status Examples : draft, published, obtained, archived (not used for the moment)
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));    
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPresentation(): ?PPBasic
    {
        return $this->presentation;
    }

    public function setPresentation(?PPBasic $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPaidService(): ?string
    {
        return $this->paidService;
    }

    public function setPaidService(?string $paidService): self
    {
        $this->paidService = $paidService;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
