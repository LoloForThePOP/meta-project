<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\ExternalContributorsStructureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExternalContributorsStructureRepository::class)
 */
class ExternalContributorsStructure
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
     * @ORM\ManyToOne(targetEntity=PPBasic::class, inversedBy="externalContributorsStructures", cascade={"persist"})
     * 
     * @ORM\JoinColumn(onDelete="CASCADE")
     * 
     */
    private $project;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $richTextContent;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity=Persorg::class, mappedBy="externalContributorsStructure", cascade={"persist", "remove"}, orphanRemoval=true)
     * 
     * @ORM\JoinColumn(onDelete="CASCADE")
     * 
     * @ORM\OrderBy({"position" = "ASC"})
     * 
     */
    private $persorgs;


    public function __construct()
    {
        $this->persorgs = new ArrayCollection();
    }

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


    public function getProject(): ?PPBasic
    {
        return $this->project;
    }

    public function setProject(?PPBasic $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getRichTextContent(): ?string
    {
        return $this->richTextContent;
    }

    public function setRichTextContent(?string $richTextContent): self
    {
        $this->richTextContent = $richTextContent;

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

    /**
     * @return Collection|Persorg[]
     */
    public function getPersorgs(): Collection
    {
        return $this->persorgs;
    }

    public function addPersorg(Persorg $persorg): self
    {
        if (!$this->persorgs->contains($persorg)) {
            $this->persorgs[] = $persorg;
            $persorg->setExternalContributorsStructure($this);
        }

        return $this;
    }

    public function removePersorg(Persorg $persorg): self
    {
        if ($this->persorgs->contains($persorg)) {
            $this->persorgs->removeElement($persorg);
            // set the owning side to null (unless already changed)
            if ($persorg->getExternalContributorsStructure() === $this) {
                $persorg->setExternalContributorsStructure(null);
            }
        }

        return $this;
    }

  
}
