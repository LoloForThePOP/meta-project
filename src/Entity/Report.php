<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 */
class Report
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reports")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 10,
     *      max = 800,
     *      minMessage = "Le Contenu du Message doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le Contenu du Message doit faire au maximum {{ limit }} caractères",
     * )
     */
    private $messageContent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $context;


    public function __construct()
    {
        $this->setCreatedAt(new \DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessageContent(): ?string
    {
        return $this->messageContent;
    }

    public function setMessageContent(string $messageContent): self
    {
        $this->messageContent = $messageContent;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): self
    {
        $this->context = $context;

        return $this;
    }
}
