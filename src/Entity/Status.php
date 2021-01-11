<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $global;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $personalized;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity=PPBasic::class, inversedBy="status", cascade={"persist", "remove"})
     */
    private $project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGlobal(): ?string
    {
        return $this->global;
    }

    public function setGlobal(?string $global): self
    {
        $this->global = $global;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getPersonalized(): ?string
    {
        return $this->personalized;
    }

    public function setPersonalized(?string $personalized): self
    {
        $this->personalized = $personalized;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getProject(): ?PPBasic
    {
        return $this->project;
    }

    public function setProject(?PPBasic $project): self
    {
        $this->project = $project;

        return $this;
    }
}
