<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;

use App\Repository\DocumentRepository;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Length;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;

use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;


/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * 
     * @Assert\Length(
    *      min = 2,
    *      max = 100,
    *      minMessage = "Le Titre du document doit faire au minimum {{ limit }} caractères",
    *      maxMessage = "Le Titre du document doit faire au maximum {{ limit }} caractères",
     * )
     * 
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=PPBasic::class, inversedBy="documents")
     */
    private $presentation;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mimeType;



    /**
     * the name of the document file (example : schedule-4234564567.pdf)
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property
     * 
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage = "Poids maximal accepté : 1 Mo",
     *     mimeTypes = {"application/pdf", "application/x-pdf", "application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/msword","application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "text/rtf", "application/vnd.oasis.opendocument.text", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/epub+zip"},
     *     mimeTypesMessage = "Veuillez sélectionner un fichier de type pdf; word; excel; powerpoint; open document texte; epub; ou rtf"
     * )
     * 
     * @Vich\UploadableField(mapping="project_document_file", fileNameProperty="fileName", size="size", mimeType="mimeType")
     * 
     * @var File|null
     */
    public $file;



    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
    */
    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        //if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        //}
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

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


    public function getId(): ?int
    {
        return $this->id;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }


    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }
	

}
