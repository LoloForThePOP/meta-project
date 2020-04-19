<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\SlideRepository")
 * @Vich\Uploadable
 */
class Slide
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * 
     * @Vich\UploadableField(mapping="slide_image", fileNameProperty="slideName")
     * 
     * @var File|null
     */
    public $slideFile;

    /**
     * the name of the image file (example : cat-4234564567.jpg)
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slideName;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mediaType;

    /**
     * (Not used) images are uploaded
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * (Not used)
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * The project presentation targeted by this slide
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\PPBasic", inversedBy="slides")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pp;

    /**
     * a slide has a position in a slideshow
     * 
     * @ORM\Column(name="position", type="smallint", nullable=true)
     */
    private $position;

    /**
     * not used for the moment
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $textContent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
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
    public function setSlideFile(?File $slideFile = null): void
    {
        $this->slideFile = $slideFile;

        if (null !== $slideFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getSlideFile(): ?File
    {
        return $this->slideFile;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
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

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }


    public function getPP(): ?PPBasic
    {
        return $this->pp;
    }

    public function setPP(?PPBasic $pp): self
    {
        $this->pp = $pp;

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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }


    public function getSlideName(): ?string
    {
        return $this->slideName;
    }

    public function setSlideName(?string $slideName): self
    {
        $this->slideName = $slideName;

        return $this;
    }

    public function getTextContent(): ?string
    {
        return $this->textContent;
    }

    public function setTextContent(?string $textContent): self
    {
        $this->textContent = $textContent;

        return $this;
    }
}
