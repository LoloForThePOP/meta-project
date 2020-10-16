<?php

namespace App\Entity;

use App\Repository\PersorgRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 * Represents a Person or an organisation (Pers Org)
 * 
 * @ORM\Entity(repositoryClass=PersorgRepository::class)
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Persorg
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
    private $name;
    
    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $missions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webdomain1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webdomain2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webdomain3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webdomain4;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     *  @Assert\Image(
     *     maxSize = "1500k",
     *     maxSizeMessage = "Poids maximal Accepté pour l'image : 1500 k",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"},
     *     mimeTypesMessage = "Le format de fichier ({{ type }}) n'est pas encore pris en compte. Les formats acceptés sont : {{ types }}"
     * )
     * @Vich\UploadableField(mapping="persorg_image", fileNameProperty="image")
     * 
     * @var File|null
     */
    public $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalContributorsStructure::class, inversedBy="persorgs", cascade={"persist", "remove"})
     * 
     */
    private $externalContributorsStructure;

    /**
     * @ORM\OneToOne(targetEntity=Owner::class, mappedBy="persorg", cascade={"persist", "remove"})
     */
    private $owner;

     

    public function __construct()
    {
        
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        //if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        //}
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }












    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMissions(): ?string
    {
        return $this->missions;
    }

    public function setMissions(?string $missions): self
    {
        $this->missions = $missions;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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







    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getWebdomain1(): ?string
    {
        return $this->webdomain1;
    }

    public function setWebdomain1(?string $webdomain1): self
    {
        $this->webdomain1 = $webdomain1;

        return $this;
    }

    public function getWebdomain2(): ?string
    {
        return $this->webdomain2;
    }

    public function setWebdomain2(?string $webdomain2): self
    {
        $this->webdomain2 = $webdomain2;

        return $this;
    }

    public function getWebdomain3(): ?string
    {
        return $this->webdomain3;
    }

    public function setWebdomain3(?string $webdomain3): self
    {
        $this->webdomain3 = $webdomain3;

        return $this;
    }

    public function getWebdomain4(): ?string
    {
        return $this->webdomain4;
    }

    public function setWebdomain4(?string $webdomain4): self
    {
        $this->webdomain4 = $webdomain4;

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

    public function getExternalContributorsStructure(): ?ExternalContributorsStructure
    {
        return $this->externalContributorsStructure;
    }

    public function setExternalContributorsStructure(?ExternalContributorsStructure $externalContributorsStructure): self
    {
        $this->externalContributorsStructure = $externalContributorsStructure;

        return $this;
    }

    public function getOwnProject(): ?PPBasic
    {
        return $this->ownProject;
    }

    public function setOwnProject(?PPBasic $ownProject): self
    {
        $this->ownProject = $ownProject;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;

        // set (or unset) the owning side of the relation if necessary
        $newPersorg = null === $owner ? null : $this;
        if ($owner->getPersorg() !== $newPersorg) {
            $owner->setPersorg($newPersorg);
        }

        return $this;
    }



}
