<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Event
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $beginDate;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $beginTime;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $endTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=PPBasic::class, inversedBy="events")
     */
    private $project;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beginYear;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beginMonth;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beginDay;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $endYear;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $endMonth;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $endDay;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $virtualBeginDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $virtualEndDate;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(?\DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getBeginTime(): ?\DateTimeInterface
    {
        return $this->beginTime;
    }

    public function setBeginTime(?\DateTimeInterface $beginTime): self
    {
        $this->beginTime = $beginTime;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

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




    public function getProject(): ?PPBasic
    {
        return $this->project;
    }

    public function setProject(?PPBasic $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getBeginYear(): ?int
    {
        return $this->beginYear;
    }

    public function setBeginYear(?int $beginYear): self
    {
        $this->beginYear = $beginYear;

        return $this;
    }

    public function getBeginMonth(): ?int
    {
        return $this->beginMonth;
    }

    public function setBeginMonth(?int $beginMonth): self
    {
        $this->beginMonth = $beginMonth;

        return $this;
    }

    public function getBeginDay(): ?int
    {
        return $this->beginDay;
    }

    public function setBeginDay(?int $beginDay): self
    {
        $this->beginDay = $beginDay;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getEndMonth(): ?int
    {
        return $this->endMonth;
    }

    public function setEndMonth(?int $endMonth): self
    {
        $this->endMonth = $endMonth;

        return $this;
    }

    public function getEndDay(): ?int
    {
        return $this->endDay;
    }

    public function setEndDay(?int $endDay): self
    {
        $this->endDay = $endDay;

        return $this;
    }

    public function getVirtualBeginDate(): ?\DateTimeInterface
    {
        return $this->virtualBeginDate;
    }

    public function setVirtualBeginDate(?\DateTimeInterface $virtualBeginDate): self
    {
        $this->virtualBeginDate = $virtualBeginDate;

        return $this;
    }

    public function getVirtualEndDate(): ?\DateTimeInterface
    {
        return $this->virtualEndDate;
    }

    public function setVirtualEndDate(?\DateTimeInterface $virtualEndDate): self
    {
        $this->virtualEndDate = $virtualEndDate;

        return $this;
    }



}
