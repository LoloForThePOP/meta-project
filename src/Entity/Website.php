<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A List of Websites an be related to a presentation of projects (ex : for this project we got a slack, a trello, a github). This Entity represents a project website.
 * 
 * @ORM\Entity(repositoryClass="App\Repository\WebsiteRepository")
 */
class Website
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PPBasic", inversedBy="websites")
     */
    private $presentation;

    /**
     * Project Presenters
     * 
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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

    public function getPresentation(): ?PPBasic
    {
        return $this->presentation;
    }

    public function setPresentation(?PPBasic $presentation): self
    {
        $this->presentation = $presentation;

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
}
