<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * For the moment, this entity concerns only project cities : a city postalCode, and a cityName). In future, maybe there will be also regions geodomains, or department geodomains, or country geodomains.
 * 
 * @ORM\Entity(repositoryClass="App\Repository\GeoDomainRepository")
 */
class GeoDomain
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "{{ limit }} Caractères Maximum pour le Code Postal"
     *      )
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "{{ limit }} Caractères Maximum pour le nom de la ville"
     *      )
     */
    private $city;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PPBasic", inversedBy="geoDomains")
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $departmentName;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $departmentCode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $country;

    public function __construct()
    {
        $this->project = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|PPBasic[]
     */
    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(PPBasic $project): self
    {
        if (!$this->project->contains($project)) {
            $this->project[] = $project;
        }

        return $this;
    }

    public function removeProject(PPBasic $project): self
    {
        if ($this->project->contains($project)) {
            $this->project->removeElement($project);
        }

        return $this;
    }

    public function getDepartmentName(): ?string
    {
        return $this->departmentName;
    }

    public function setDepartmentName(?string $departmentName): self
    {
        $this->departmentName = $departmentName;

        return $this;
    }

    public function getDepartmentCode(): ?string
    {
        return $this->departmentCode;
    }

    public function setDepartmentCode(?string $departmentCode): self
    {
        $this->departmentCode = $departmentCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }
}
