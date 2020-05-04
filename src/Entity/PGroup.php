<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PGroupRepository")
 */
class PGroup
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
     * @ORM\Column(type="string", length=600, nullable=true)
     */
    private $description;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdPGroups")
     */
    private $creator;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="pGroupsMaster")
     */
    private $masters;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PPBasic", inversedBy="inPGroups")
     * @JoinTable(name="included_projects")
     */
    private $includedP;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PPBasic", inversedBy="applyToPGroups")
     * @JoinTable(name="candidates_projects")
     */
    private $candidatesP;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PPBasic", inversedBy="invitedByPGroups")
     * @JoinTable(name="invited_projects")
     */
    private $invitedP;


    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->masters = new ArrayCollection();
        $this->includedP = new ArrayCollection();
        $this->candidatesP = new ArrayCollection();
        $this->invitedP = new ArrayCollection();
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

    public function getCreator(): ?user
    {
        return $this->creator;
    }

    public function setCreator(?user $creator): self
    {
        $this->creator = $creator;

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

    /**
     * @return Collection|user[]
     */
    public function getMasters(): Collection
    {
        return $this->masters;
    }

    public function addMaster(user $master): self
    {
        if (!$this->masters->contains($master)) {
            $this->masters[] = $master;
        }

        return $this;
    }

    public function removeMaster(user $master): self
    {
        if ($this->masters->contains($master)) {
            $this->masters->removeElement($master);
        }

        return $this;
    }

    /**
     * @return Collection|PPBasic[]
     */
    public function getIncludedP(): Collection
    {
        return $this->includedP;
    }

    public function addIncludedP(PPBasic $includedP): self
    {
        if (!$this->includedP->contains($includedP)) {
            $this->includedP[] = $includedP;
        }

        return $this;
    }

    public function removeIncludedP(PPBasic $includedP): self
    {
        if ($this->includedP->contains($includedP)) {
            $this->includedP->removeElement($includedP);
        }

        return $this;
    }

    /**
     * @return Collection|PPBasic[]
     */
    public function getCandidatesP(): Collection
    {
        return $this->candidatesP;
    }

    public function addCandidateP(PPBasic $candidateP): self
    {
        if (!$this->candidatesP->contains($candidateP)) {
            $this->candidatesP[] = $candidateP;
        }

        return $this;
    }

    public function removeCandidatesP(PPBasic $candidatesP): self
    {
        if ($this->candidatesP->contains($candidatesP)) {
            $this->candidatesP->removeElement($candidatesP);
        }

        return $this;
    }

    /**
     * @return Collection|PPBasic[]
     */
    public function getInvitedP(): Collection
    {
        return $this->invitedP;
    }

    public function addInvitedP(PPBasic $invitedP): self
    {
        if (!$this->invitedP->contains($invitedP)) {
            $this->invitedP[] = $invitedP;
        }

        return $this;
    }

    public function removeInvitedP(PPBasic $invitedP): self
    {
        if ($this->invitedP->contains($invitedP)) {
            $this->invitedP->removeElement($invitedP);
        }

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
}
