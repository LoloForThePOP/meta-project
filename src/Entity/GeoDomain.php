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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $geoType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $AdministrativeAreaLevel1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $AdministrativeAreaLevel2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $route;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sublocalityLevel1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeName;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getGeoType(): ?string
    {
        return $this->geoType;
    }

    public function setGeoType(?string $geoType): self
    {
        $this->geoType = $geoType;

        return $this;
    }

    public function getAdministrativeAreaLevel1(): ?string
    {
        return $this->AdministrativeAreaLevel1;
    }

    public function setAdministrativeAreaLevel1(?string $AdministrativeAreaLevel1): self
    {
        $this->AdministrativeAreaLevel1 = $AdministrativeAreaLevel1;

        return $this;
    }

    public function getAdministrativeAreaLevel2(): ?string
    {
        return $this->AdministrativeAreaLevel2;
    }

    public function setAdministrativeAreaLevel2(?string $AdministrativeAreaLevel2): self
    {
        $this->AdministrativeAreaLevel2 = $AdministrativeAreaLevel2;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getSublocalityLevel1(): ?string
    {
        return $this->sublocalityLevel1;
    }

    public function setSublocalityLevel1(?string $sublocalityLevel1): self
    {
        $this->sublocalityLevel1 = $sublocalityLevel1;

        return $this;
    }

    public function getPlaceName(): ?string
    {
        return $this->placeName;
    }

    public function setPlaceName(?string $placeName): self
    {
        $this->placeName = $placeName;

        return $this;
    }
}
