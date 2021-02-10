<?php

namespace App\Entity;

use DateTimeImmutable;

use DateTimeInterface;


use Cocur\Slugify\Slugify;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\HttpFoundation\File\File;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PPBasicRepository")
 * @ORM\Table(name="ppbasic", indexes={@ORM\Index(columns={"title", "goal","keywords"}, flags={"fulltext"})})
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */
class PPBasic implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;




    /**
     * the name of the image file (example : cat-4234564567.jpg)
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnailName;

    
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     *  @Assert\Image(
     *     maxSize = "1500k",
     *     maxSizeMessage = "Poids maximal Accepté pour l'image : 1500 k",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"},
     *     mimeTypesMessage = "Le format de fichier ({{ type }}) n'est pas encore pris en compte. Les formats acceptés sont : {{ types }}"
     * )
     * 
     * @Vich\UploadableField(mapping="project_logo_thumbnail", fileNameProperty="thumbnailName")
     * 
     * @var File|null
     */
    public $thumbnailFile;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $thumbnailFile
     */
    public function setThumbnailFile(?File $thumbnailFile = null): void
    {
        $this->thumbnailFile = $thumbnailFile;

        //if (null !== $thumbnailFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        //}
    }

    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    
    public function getThumbnailName(): ?string
    {
        return $this->thumbnailName;
    }

    public function setThumbnailName(?string $thumbnailName): self
    {
        $this->thumbnailName = $thumbnailName;

        return $this;
    }







    /**
     * the name of the logo image file (example : logo-4234564567.jpg)
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logoName;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     *  @Assert\Image(
     *     maxSize = "1500k",
     *     maxSizeMessage = "Poids maximal Accepté pour l'image : 1500 k",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"},
     *     mimeTypesMessage = "Le format de fichier ({{ type }}) n'est pas encore pris en compte. Les formats acceptés sont : {{ types }}"
     * )
     * @Vich\UploadableField(mapping="project_logo_image", fileNameProperty="logoName")
     * 
     * @var File|null
     */
    public $logoFile;


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $logoFile
     */
    public function setLogoFile(?File $logoFile = null): void
    {
        $this->logoFile = $logoFile;

        //if (null !== $logoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        //}
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    
    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoName(?string $logoName): self
    {
        $this->logoName = $logoName;

        return $this;
    }











    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "L'objectif doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "L'objectif doit contenir au plus {{ limit }} caractères"
     *      )
     */
    private $goal;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;
    
    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Slide", mappedBy="pp", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $slides;

    
    public const MAX_ALLOWED_SLIDES = 8;
    

    function getMaxAllowedSlides() 
    {
        return  self::MAX_ALLOWED_SLIDES;
    }


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Need", mappedBy="presentation", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $needs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="presentations", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="set null")
     */
    private $creator;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contact", mappedBy="presentation", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $contacts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", mappedBy="project")
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GeoDomain", mappedBy="project", orphanRemoval=true)
     */
    private $geoDomains;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Website", mappedBy="presentation", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $websites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ContactMessage", mappedBy="presentation")
     */
    private $contactMessages;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActiveContactMessages;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PGroup", mappedBy="includedP")
     */
    private $inPGroups;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PGroup", mappedBy="candidatesP")
     * 
     */
    private $applyToPGroups;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PGroup", mappedBy="invitedP")
     * 
     */
    private $invitedByPGroups;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $adminValidation;


    /**
     * @ORM\OneToMany(targetEntity=QuestionAnswer::class, mappedBy="presentation")
     * 
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $questionAnswers;

    /**
     * @ORM\OneToOne(targetEntity=TextDescription::class, mappedBy="presentation", cascade={"persist", "remove"})
     */
    private $textDescription;




    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="presentation")
     * 
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $documents;

    public const MAX_ALLOWED_DOCUMENTS = 7;
    

    function getMaxAllowedDocuments() 
    {
        return  self::MAX_ALLOWED_DOCUMENTS;
    }





    /**
     * @ORM\OneToMany(targetEntity=Teammate::class, mappedBy="project")
     * 
     * @ORM\OrderBy({"position" = "ASC"})
     * 
     */
    private $teammates;
    
    public const MAX_ALLOWED_TEAMMATES = 100;
    

    function getMaxAllowedTeammates() 
    {
        return  self::MAX_ALLOWED_TEAMMATES;
    }




    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="project")
     * 
     * @ORM\OrderBy({"position" = "ASC"})
     * 
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity=TechnicalData::class, mappedBy="presentation")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $technicalData;

    /**
     * @ORM\OneToMany(targetEntity=ExternalContributorsStructure::class, mappedBy="project", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     * 
     */
    private $externalContributorsStructures;

    /**
     * @ORM\OneToMany(targetEntity=Owner::class, mappedBy="project")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $owners;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="presentation")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $allowComments;

    /**
     * 
     * Some presentation can be of a very bad quality. We don't want these presentation to be displayed on homepage for example. overallQualityAssessment is a number between 0 and 5. 0 means very bad quality, 5 means exccellent quality.
     * 
     * To do : create an algorithm that automatically assess the quality of a presentation (an event should be triggered each time a presentation component is created, or deleted, or updated, this event calls a function called presentationQualityAssessment)
     * 
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $overallQualityAssessment;

    /**
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="project")
     * 
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $news;


    /**
     * @ORM\OneToMany(targetEntity=UserFollows::class, mappedBy="presentation")
     */
    private $usersFollow;

    /**
     * @ORM\OneToOne(targetEntity=PPMajorLogs::class, mappedBy="presentation", cascade={"persist", "remove"})
     */
    private $majorLogs;

    /**
     * @ORM\OneToOne(targetEntity=Status::class, mappedBy="project", cascade={"persist", "remove"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Right::class, mappedBy="presentation")
     */
    private $rights;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accessCode;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $teammatesByText;


 





    public function __construct()
    {
        $this->adminValidation = true;
        $this->isPublished = true;
        $this->isActiveContactMessages = true;
        $this->overallQualityAssessment = 3;
        $this->allowComments = true;
        $this->accessCode = mt_rand(100000, 999999);
        $this->slides = new ArrayCollection();
        $this->needs = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->geoDomains = new ArrayCollection();
        $this->websites = new ArrayCollection();
        $this->contactMessages = new ArrayCollection();
        $this->inPGroups = new ArrayCollection();
        $this->applyToPGroups = new ArrayCollection();
        $this->invitedByPGroups = new ArrayCollection();
        $this->questionAnswers = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->teammates = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->technicalData = new ArrayCollection();
        $this->externalContributorsStructures = new ArrayCollection();
        $this->owners = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->news = new ArrayCollection();
        $this->usersFollow = new ArrayCollection();
        $this->rights = new ArrayCollection();
    }
    

       
    public function __toString()
    {
        return (string) $this->id.' '.$this->title;
    }
 

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


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
    */
    public function timestampsUpdate(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }

        $this->setUpdatedAt(new \DateTime('now'));
    }


    
     /**
     * Unique Slug Creation
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function uniqueSlug(){

        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug=$slugify->slugify(substr($this->goal,0,90)).'-'.$this->id;
        }

    }


    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): self
    {
        $this->goal = $goal;

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

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }


    /**
     * @return Collection|Slide[]
     */
    public function getSlides(): Collection
    {
        return $this->slides;
    }

    public function addSlide(Slide $slide): self
    {
        if (!$this->slides->contains($slide)) {
            $this->slides[] = $slide;
            $slide->setPp($this);
        }

        return $this;
    }

    public function removeSlide(Slide $slide): self
    {
        if ($this->slides->contains($slide)) {
            $this->slides->removeElement($slide);
            // set the owning side to null (unless already changed)
            if ($slide->getPp() === $this) {
                $slide->setPp(null);
            }
        }

        return $this;
    }





    /**
     * @return Collection|Need[]
     */
    public function getNeeds(): Collection
    {
        return $this->needs;
    }

    public function addNeed(Need $need): self
    {
        if (!$this->needs->contains($need)) {
            $this->needs[] = $need;
            $need->setPresentation($this);
        }

        return $this;
    }

    public function removeNeed(Need $need): self
    {
        if ($this->needs->contains($need)) {
            $this->needs->removeElement($need);
            // set the owning side to null (unless already changed)
            if ($need->getPresentation() === $this) {
                $need->setPresentation(null);
            }
        }

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

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setPresentation($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getPresentation() === $this) {
                $contact->setPresentation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategories(Category $categories): self
    {
        if (!$this->categories->contains($categories)) {
            $this->categories[] = $categories;
            $categories->addProject($this);
        }

        return $this;
    }

    public function removeCategories(Category $categories): self
    {
        if ($this->categories->contains($categories)) {
            $this->categories->removeElement($categories);
            $categories->removeProject($this);
        }

        return $this;
    }

    /**
     * @return Collection|GeoDomain[]
     */
    public function getGeoDomains(): Collection
    {
        return $this->geoDomains;
    }

    public function addGeoDomain(GeoDomain $geoDomain): self
    {
        if (!$this->geoDomains->contains($geoDomain)) {
            $this->geoDomains[] = $geoDomain;
            $geoDomain->addProject($this);
        }

        return $this;
    }

    public function removeGeoDomain(GeoDomain $geoDomain): self
    {
        if ($this->geoDomains->contains($geoDomain)) {
            $this->geoDomains->removeElement($geoDomain);
            $geoDomain->removeProject($this);
        }

        return $this;
    }

    /**
     * @return Collection|Website[]
     */
    public function getWebsites(): Collection
    {
        return $this->websites;
    }

    public function addWebsite(Website $website): self
    {
        if (!$this->websites->contains($website)) {
            $this->websites[] = $website;
            $website->setPresentation($this);
        }

        return $this;
    }

    public function removeWebsite(Website $website): self
    {
        if ($this->websites->contains($website)) {
            $this->websites->removeElement($website);
            // set the owning side to null (unless already changed)
            if ($website->getPresentation() === $this) {
                $website->setPresentation(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|ContactMessage[]
     */
    public function getContactMessages(): Collection
    {
        return $this->contactMessages;
    }

    public function addContactMessage(ContactMessage $contactMessage): self
    {
        if (!$this->contactMessages->contains($contactMessage)) {
            $this->contactMessages[] = $contactMessage;
            $contactMessage->setPresentation($this);
        }

        return $this;
    }

    public function removeContactMessage(ContactMessage $contactMessage): self
    {
        if ($this->contactMessages->contains($contactMessage)) {
            $this->contactMessages->removeElement($contactMessage);
            // set the owning side to null (unless already changed)
            if ($contactMessage->getPresentation() === $this) {
                $contactMessage->setPresentation(null);
            }
        }

        return $this;
    }

    public function countUnreadMessages()
    {
        $count = 0;

        foreach ($this->getContactMessages() as $item) {

            if ($item->getHasBeenConsulted() == false) { 
                $count++;
            }
        }

        return $count;
    }

    public function getIsActiveContactMessages(): ?bool
    {
        return $this->isActiveContactMessages;
    }

    public function setIsActiveContactMessages(?bool $isActiveContactMessages): self
    {
        $this->isActiveContactMessages = $isActiveContactMessages;

        return $this;
    }

    /**
     * @return Collection|PGroup[]
     */
    public function getInPGroups(): Collection
    {
        return $this->inPGroups;
    }

    public function addInPGroup(PGroup $pGroup): self
    {
        if (!$this->inPGroups->contains($pGroup)) {
            $this->inPGroups[] = $pGroup;
            $pGroup->addIncludedP($this);
        }

        return $this;
    }

    public function removeInPGroup(PGroup $pGroup): self
    {
        if ($this->inPGroups->contains($pGroup)) {
            $this->inPGroups->removeElement($pGroup);
            $pGroup->removeIncludedP($this);
        }

        return $this;
    }

    /**
     * @return Collection|PGroup[]
     */
    public function getApplyToPGroups(): Collection
    {
        return $this->applyToPGroups;
    }

    public function addApplyToPGroup(PGroup $pGroup): self
    {
        if (!$this->applyToPGroups->contains($pGroup)) {
            $this->applyToPGroups[] = $pGroup;
            $pGroup->addCandidatesP($this);
        }

        return $this;
    }

    public function removeApplyToPGroup(PGroup $applyToPGroup): self
    {
        if ($this->applyToPGroups->contains($applyToPGroup)) {
            $this->applyToPGroups->removeElement($applyToPGroup);
            $applyToPGroup->removeCandidatesP($this);
        }

        return $this;
    }

    /**
     * @return Collection|PGroup[]
     */
    public function getInvitedByPGroups(): Collection
    {
        return $this->invitedByPGroups;
    }

    public function addInvitedByPGroup(PGroup $invitedByPGroup): self
    {
        if (!$this->invitedByPGroups->contains($invitedByPGroup)) {
            $this->invitedByPGroups[] = $invitedByPGroup;
            $invitedByPGroup->addInvitedP($this);
        }

        return $this;
    }

    public function removeInvitedByPGroup(PGroup $invitedByPGroup): self
    {
        if ($this->invitedByPGroups->contains($invitedByPGroup)) {
            $this->invitedByPGroups->removeElement($invitedByPGroup);
            $invitedByPGroup->removeInvitedP($this);
        }

        return $this;
    }

    
    public function serialize()
    {
        return serialize($this->id);
    }
    
    public function unserialize($serialized)
    {
    $this->id = unserialize($serialized);
    
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getAdminValidation(): ?bool
    {
        return $this->adminValidation;
    }

    public function setAdminValidation(?bool $adminValidation): self
    {
        $this->adminValidation = $adminValidation;

        return $this;
    }


    /**
     * @return Collection|QuestionAnswer[]
     */
    public function getQuestionAnswers(): Collection
    {
        return $this->questionAnswers;
    }

    public function addQuestionAnswer(QuestionAnswer $questionAnswer): self
    {
        if (!$this->questionAnswers->contains($questionAnswer)) {
            $this->questionAnswers[] = $questionAnswer;
            $questionAnswer->setPresentation($this);
        }

        return $this;
    }

    public function removeQuestionAnswer(QuestionAnswer $questionAnswer): self
    {
        if ($this->questionAnswers->contains($questionAnswer)) {
            $this->questionAnswers->removeElement($questionAnswer);
            // set the owning side to null (unless already changed)
            if ($questionAnswer->getPresentation() === $this) {
                $questionAnswer->setPresentation(null);
            }
        }

        return $this;
    }

    public function getTextDescription(): ?TextDescription
    {
        return $this->textDescription;
    }

    public function setTextDescription(?TextDescription $textDescription): self
    {
        $this->textDescription = $textDescription;

        // set (or unset) the owning side of the relation if necessary
        $newPresentation = null === $textDescription ? null : $this;
        if ($textDescription->getPresentation() !== $newPresentation) {
            $textDescription->setPresentation($newPresentation);
        }

        return $this;
    }

    /**
     * @return Collection|Document[]
     * 
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setPresentation($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getPresentation() === $this) {
                $document->setPresentation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Teammate[]
     */
    public function getTeammates(): Collection
    {
        return $this->teammates;
    }

    public function addTeammate(Teammate $teammate): self
    {
        if (!$this->teammates->contains($teammate)) {
            $this->teammates[] = $teammate;
            $teammate->setProject($this);
        }

        return $this;
    }

    public function removeTeammate(Teammate $teammate): self
    {
        if ($this->teammates->contains($teammate)) {
            $this->teammates->removeElement($teammate);
            // set the owning side to null (unless already changed)
            if ($teammate->getProject() === $this) {
                $teammate->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setProject($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getProject() === $this) {
                $event->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TechnicalData[]
     */
    public function getTechnicalData(): Collection
    {
        return $this->technicalData;
    }

    public function addTechnicalData(TechnicalData $technicalData): self
    {
        if (!$this->technicalData->contains($technicalData)) {
            $this->technicalData[] = $technicalData;
            $technicalData->setPresentation($this);
        }

        return $this;
    }

    public function removeTechnicalData(TechnicalData $technicalData): self
    {
        if ($this->technicalData->contains($technicalData)) {
            $this->technicalData->removeElement($technicalData);
            // set the owning side to null (unless already changed)
            if ($technicalData->getPresentation() === $this) {
                $technicalData->setPresentation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExternalContributorsStructure[]
     */
    public function getExternalContributorsStructures(): Collection
    {
        return $this->externalContributorsStructures;
    }

    public function addExternalContributorsStructure(ExternalContributorsStructure $externalContributorsStructure): self
    {
        if (!$this->externalContributorsStructures->contains($externalContributorsStructure)) {
            $this->externalContributorsStructures[] = $externalContributorsStructure;
            $externalContributorsStructure->setProject($this);
        }

        return $this;
    }

    public function removeExternalContributorsStructure(ExternalContributorsStructure $externalContributorsStructure): self
    {
        if ($this->externalContributorsStructures->contains($externalContributorsStructure)) {
            $this->externalContributorsStructures->removeElement($externalContributorsStructure);
            // set the owning side to null (unless already changed)
            if ($externalContributorsStructure->getProject() === $this) {
                $externalContributorsStructure->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Owner[]
     */
    public function getOwners(): Collection
    {
        return $this->owners;
    }

    public function addOwner(Owner $owner): self
    {
        if (!$this->owners->contains($owner)) {
            $this->owners[] = $owner;
            $owner->setProject($this);
        }

        return $this;
    }

    public function removeOwner(Owner $owner): self
    {
        if ($this->owners->contains($owner)) {
            $this->owners->removeElement($owner);
            // set the owning side to null (unless already changed)
            if ($owner->getProject() === $this) {
                $owner->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPresentation($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPresentation() === $this) {
                $comment->setPresentation(null);
            }
        }

        return $this;
    }

    public function getAllowComments(): ?string
    {
        return $this->allowComments;
    }

    public function setAllowComments(?string $allowComments): self
    {
        $this->allowComments = $allowComments;

        return $this;
    }

    public function getOverallQualityAssessment(): ?int
    {
        return $this->overallQualityAssessment;
    }

    public function setOverallQualityAssessment(?int $overallQualityAssessment): self
    {
        $this->overallQualityAssessment = $overallQualityAssessment;

        return $this;
    }

    /**
     * @return Collection|News[]
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->setProject($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->removeElement($news)) {
            // set the owning side to null (unless already changed)
            if ($news->getProject() === $this) {
                $news->setProject(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection|UserFollows[]
     */
    public function getUsersFollow(): Collection
    {
        return $this->usersFollow;
    }

    public function addUsersFollow(UserFollows $usersFollow): self
    {
        if (!$this->usersFollow->contains($usersFollow)) {
            $this->usersFollow[] = $usersFollow;
            $usersFollow->setPresentation($this);
        }

        return $this;
    }


    public function removeUsersFollow(UserFollows $usersFollow): self
    {
        if ($this->usersFollow->removeElement($usersFollow)) {
            // set the owning side to null (unless already changed)
            if ($usersFollow->getPresentation() === $this) {
                $usersFollow->setPresentation(null);
            }
        }

        return $this;
    }

    
    public function isFollowedBy(User $user) : bool
    {

        $followRows = $this->getUsersFollow();

        if ($followRows !== null) {
           
            foreach ($followRows as $followRow) {

                if($followRow->getUser() == $user)
                {
                    return true;
                }

            }

            return false;

        }
        
    }


    /**
     * Allow to know wether an user can access this presentation with a specific right
     *
     * @param User $user
     * @param string $rightType
     * @return boolean
     */
    
    public function isAccessedBy(User $user, $rightType) : bool
    {

        // if user created presentation, he has all access by default

        if ($user == $this->getCreator()) {
            return true;
        }

        $presentationRights = $this->getRights();

        if ($presentationRights !== null) {
           
            foreach ($presentationRights as $right) {

                if($right->getUser() == $user && $right->getType() == $rightType)
                {
                    return true;
                }

            }
            return false;
        }
        
    }




    public function getMajorLogs(): ?PPMajorLogs
    {
        return $this->majorLogs;
    }

    public function setMajorLogs(PPMajorLogs $majorLogs): self
    {
        // set the owning side of the relation if necessary
        if ($majorLogs->getPresentation() !== $this) {
            $majorLogs->setPresentation($this);
        }

        $this->majorLogs = $majorLogs;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        // unset the owning side of the relation if necessary
        if ($status === null && $this->status !== null) {
            $this->status->setProject(null);
        }

        // set the owning side of the relation if necessary
        if ($status !== null && $status->getProject() !== $this) {
            $status->setProject($this);
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Right[]
     */
    public function getRights(): Collection
    {
        return $this->rights;
    }

    public function addRight(Right $right): self
    {
        if (!$this->rights->contains($right)) {
            $this->rights[] = $right;
            $right->setPresentation($this);
        }

        return $this;
    }

    public function removeRight(Right $right): self
    {
        if ($this->rights->removeElement($right)) {
            // set the owning side to null (unless already changed)
            if ($right->getPresentation() === $this) {
                $right->setPresentation(null);
            }
        }

        return $this;
    }

    public function getAccessCode(): ?int
    {
        return $this->accessCode;
    }

    public function setAccessCode(?int $accessCode): self
    {
        $this->accessCode = $accessCode;

        return $this;
    }

    public function getTeammatesByText(): ?string
    {
        return $this->teammatesByText;
    }

    public function setTeammatesByText(?string $teammatesByText): self
    {
        $this->teammatesByText = $teammatesByText;

        return $this;
    }



  



}
