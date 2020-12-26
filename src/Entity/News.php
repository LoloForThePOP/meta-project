<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping\PreUpdate;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

/**
 * Represents a project news
 * 
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks
 * 
 * @Vich\Uploadable
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PPBasic::class, inversedBy="news")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="news")
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;



        
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image1;
        
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image2;
        
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image3;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     *  @Assert\Image(
     *     maxSize = "1500k",
     *     maxSizeMessage = "Poids maximal Accepté pour l'image : 1500 k",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"},
     *     mimeTypesMessage = "Le format de fichier ({{ type }}) n'est pas encore pris en compte. Les formats acceptés sont : {{ types }}"
     * )
     * @Vich\UploadableField(mapping="news_image", fileNameProperty="image1")
     * 
     * @var File|null
     */
    public $image1File;

    
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $image1File
     */
    public function setImage1File(?File $image1File = null): void
    {
        $this->image1File = $image1File;

        //if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->vichUpdatedAt = new \DateTimeImmutable();
        //}
    }

    public function getImage1File(): ?File
    {
        return $this->image1File;
    }

    
    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(?string $image1): self
    {
        $this->image1 = $image1;

        return $this;
    }


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     *  @Assert\Image(
     *     maxSize = "1500k",
     *     maxSizeMessage = "Poids maximal Accepté pour l'image : 1500 k",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"},
     *     mimeTypesMessage = "Le format de fichier ({{ type }}) n'est pas encore pris en compte. Les formats acceptés sont : {{ types }}"
     * )
     * @Vich\UploadableField(mapping="news_image", fileNameProperty="image2")
     * 
     * @var File|null
     */
    public $image2File;

    
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $image2File
     */
    public function setImage2File(?File $image2File = null): void
    {
        $this->image2File = $image2File;

        //if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->vichUpdatedAt = new \DateTimeImmutable();
        //}
    }

    public function getImage2File(): ?File
    {
        return $this->image2File;
    }

    
    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(?string $image2): self
    {
        $this->image2 = $image2;

        return $this;
    }





    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     *  @Assert\Image(
     *     maxSize = "1500k",
     *     maxSizeMessage = "Poids maximal Accepté pour l'image : 1500 k",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"},
     *     mimeTypesMessage = "Le format de fichier ({{ type }}) n'est pas encore pris en compte. Les formats acceptés sont : {{ types }}"
     * )
     * @Vich\UploadableField(mapping="news_image", fileNameProperty="image3")
     * 
     * @var File|null
     */
    public $image3File;

    
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $image3File
     */
    public function setImage3File(?File $image3File = null): void
    {
        $this->image3File = $image3File;

        //if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->vichUpdatedAt = new \DateTimeImmutable();
        //}
    }

    public function getImage3File(): ?File
    {
        return $this->image3File;
    }

    
    public function getImage3(): ?string
    {
        return $this->image3;
    }

    public function setImage3(?string $image3): self
    {
        $this->image3 = $image3;

        return $this;
    }

















    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video3;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * make vich upload bundle work ("hack")
     * 
     * @ORM\Column(type="datetime", nullable=true)
     *
     */
    private $vichUpdatedAt;

    /**
     * @ORM\Column(type="text")
     */
    private $textContent;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $captionImage1;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $captionImage2;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $captionImage3;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $captionVideo1;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $captionVideo2;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $captionVideo3;










    public function __construct()
    {

        $this->createdAt = new \DateTime('now');

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

    public function getProject(): ?PPBasic
    {
        return $this->project;
    }

    public function setProject(?PPBasic $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getCreator(): ?user
    {
        return $this->creator;
    }

    public function setCreator(?user $creator): self
    {
        $this->creator = $creator;

        return $this;
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



    public function getVideo1(): ?string
    {
        return $this->video1;
    }

    public function setVideo1(?string $video1): self
    {
        $this->video1 = $video1;

        return $this;
    }

    public function getVideo2(): ?string
    {
        return $this->video2;
    }

    public function setVideo2(?string $video2): self
    {
        $this->video2 = $video2;

        return $this;
    }

    public function getVideo3(): ?string
    {
        return $this->video3;
    }

    public function setVideo3(?string $video3): self
    {
        $this->video3 = $video3;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getVichUpdatedAt(): ?\DateTimeInterface
    {
        return $this->vichUpdatedAt;
    }

    public function setVichUpdatedAt(?\DateTimeInterface $vichUpdatedAt): self
    {
        $this->vichUpdatedAt = $vichUpdatedAt;

        return $this;
    }

    public function getTextContent(): ?string
    {
        return $this->textContent;
    }

    public function setTextContent(string $textContent): self
    {
        $this->textContent = $textContent;

        return $this;
    }

    public function getCaptionImage1(): ?string
    {
        return $this->captionImage1;
    }

    public function setCaptionImage1(?string $captionImage1): self
    {
        $this->captionImage1 = $captionImage1;

        return $this;
    }

    public function getCaptionImage2(): ?string
    {
        return $this->captionImage2;
    }

    public function setCaptionImage2(?string $captionImage2): self
    {
        $this->captionImage2 = $captionImage2;

        return $this;
    }

    public function getCaptionImage3(): ?string
    {
        return $this->captionImage3;
    }

    public function setCaptionImage3(?string $captionImage3): self
    {
        $this->captionImage3 = $captionImage3;

        return $this;
    }

    public function getCaptionVideo1(): ?string
    {
        return $this->captionVideo1;
    }

    public function setCaptionVideo1(?string $captionVideo1): self
    {
        $this->captionVideo1 = $captionVideo1;

        return $this;
    }

    public function getCaptionVideo2(): ?string
    {
        return $this->captionVideo2;
    }

    public function setCaptionVideo2(?string $captionVideo2): self
    {
        $this->captionVideo2 = $captionVideo2;

        return $this;
    }

    public function getCaptionVideo3(): ?string
    {
        return $this->captionVideo3;
    }

    public function setCaptionVideo3(?string $captionVideo3): self
    {
        $this->captionVideo3 = $captionVideo3;

        return $this;
    }
}
