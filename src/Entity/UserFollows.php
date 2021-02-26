<?php

namespace App\Entity;

use App\Repository\UserFollowsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserFollowsRepository::class)
 */
class UserFollows
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="boolean")
     */
    private $emailNotifications;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userFollows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=PPBasic::class, inversedBy="usersFollow")
     * @ORM\JoinColumn(onDelete="SET NULL"))
     */
    private $presentation;

    
    public function __construct()
    {
        $this->emailNotifications = false;

        $this->lastConnexion = new \DateTime('now');

    }

    public function getId(): ?int
    {
        return $this->id;
    }
    

    public function getEmailNotifications(): ?bool
    {
        return $this->emailNotifications;
    }

    public function setEmailNotifications(bool $emailNotifications): self
    {
        $this->emailNotifications = $emailNotifications;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
