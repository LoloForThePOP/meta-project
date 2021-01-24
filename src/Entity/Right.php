<?php

namespace App\Entity;

use App\Repository\RightRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RightRepository::class)
 * @ORM\Table(name="`right`")
 */
class Right
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PPBasic::class, inversedBy="rights")
     */
    private $presentation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rights")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
