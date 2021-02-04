<?php

namespace App\Entity;

use App\Repository\TeammateRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=TeammateRepository::class)
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Teammate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=PPBasic::class, inversedBy="teammates")
     */
    private $project;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    /**
     * @ORM\OneToOne(targetEntity=Persorg::class, inversedBy="teammate", cascade={"persist", "remove"})
     */
    private $persorg;




    public function getId(): ?int
    {
        return $this->id;
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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    
    /**
    * Allow to Automaticaly set CreatedAt
    * 
    * @ORM\PrePersist
    */
    public function createTimestamp(): void
    {
          
        if ($this->getCreatedAt() === null) {

            $this->setCreatedAt(new \DateTime('now'));

        }

    }

    /**
    * Allow to Automaticaly setUpdatedAt
    * 
    * @ORM\PreUpdate
    */
    public function updatedTimestamp(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
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


    public function getPosition(): ?int
    {
        return $this->position;
    }


    public function setPosition(?int $position): self
    {
        $this->position = $position;

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



}
