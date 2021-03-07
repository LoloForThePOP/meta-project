<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Each Project can have some "Contact Us Zones" 
 * 
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postalMail;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $remarks;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showEmails;

    /**
     * The Project Presentation whereby this contact is related
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\PPBasic", inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website3;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPostalMail(): ?string
    {
        return $this->postalMail;
    }

    public function setPostalMail(?string $postalMail): self
    {
        $this->postalMail = $postalMail;

        return $this;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(?string $remarks): self
    {
        $this->remarks = $remarks;

        return $this;
    }

    public function getShowEmails(): ?bool
    {
        return $this->showEmails;
    }

    public function setShowEmails(?bool $showEmails): self
    {
        $this->showEmails = $showEmails;

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

    public function getEmail1(): ?string
    {
        return $this->email1;
    }

    public function setEmail1(?string $email1): self
    {
        $this->email1 = $email1;

        return $this;
    }

    public function getEmail2(): ?string
    {
        return $this->email2;
    }

    public function setEmail2(?string $email2): self
    {
        $this->email2 = $email2;

        return $this;
    }

    public function getEmail3(): ?string
    {
        return $this->email3;
    }

    public function setEmail3(?string $email3): self
    {
        $this->email3 = $email3;

        return $this;
    }

    public function getTel1(): ?string
    {
        return $this->tel1;
    }

    public function setTel1(?string $tel1): self
    {
        $this->tel1 = $tel1;

        return $this;
    }

    public function getTel2(): ?string
    {
        return $this->tel2;
    }

    public function setTel2(?string $tel2): self
    {
        $this->tel2 = $tel2;

        return $this;
    }

    public function getTel3(): ?string
    {
        return $this->tel3;
    }

    public function setTel3(?string $tel3): self
    {
        $this->tel3 = $tel3;

        return $this;
    }

    public function getWebsite1(): ?string
    {
        return $this->website1;
    }

    public function setWebsite1(?string $website1): self
    {
        $this->website1 = $website1;

        return $this;
    }

    public function getWebsite2(): ?string
    {
        return $this->website2;
    }

    public function setWebsite2(?string $website2): self
    {
        $this->website2 = $website2;

        return $this;
    }

    public function getWebsite3(): ?string
    {
        return $this->website3;
    }

    public function setWebsite3(?string $website3): self
    {
        $this->website3 = $website3;

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
