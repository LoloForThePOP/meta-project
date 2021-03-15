<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Constraints\Url;

/**
 * 
 * Allow to create a List of Websites related to a project (ex : "for this project we got : a slack, a trello, a github").
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Url(
     *    message = "l'adresse '{{ value }}' n'est pas une url valide",
     * )
     * 
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "La description doit faire au maximum {{ limit }} caractères",
     * )
     *
     */
    private $description;

    /**
     * 
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PPBasic", inversedBy="websites")
     * 
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * 
     * Type of website
     * Examples : 'general'; 'poll'.
     *  
     */
    private $type;


    
    public function __construct()
    {
        $this->type = 'general';
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(? string $url): self
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
    
}
