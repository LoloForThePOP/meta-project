<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactMessageRepository")
 */
class ContactMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 
     * The context whereby this message is sent (ex: a visitor send a message for a specific project need)
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $context;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 5,
     *      max = 800,
     *      minMessage = "Le Titre du Message doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le Titre du Message doit faire au maximum {{ limit }} caractères",
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=800)
     * @Assert\Length(
     *      min = 10,
     *      max = 800,
     *      minMessage = "Le Contenu du Message doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le Contenu du Message doit faire au maximum {{ limit }} caractères",
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner un email valide")
     */
    private $senderEmail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PPBasic", inversedBy="contactMessages")
     */
    private $presentation;

    /**
     * Users who will receive the message (for the moment, only project presentation creator will receive the mesage (this property is accesssed via above $presentation attribute))
     * 
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="contactMessages")
     */
    private $receivers;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasBeenConsulted;




    public function __construct()
    {
        $this->receivers = new ArrayCollection();
        $this->setCreatedAt(new \DateTime('now'));
        $this->hasBeenConsulted = false;
    }
  
    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    public function setSenderEmail(string $senderEmail): self
    {
        $this->senderEmail = $senderEmail;

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

    public function getPresentation(): ?PPBasic
    {
        return $this->presentation;
    }

    public function setPresentation(?PPBasic $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getReceivers(): Collection
    {
        return $this->receivers;
    }

    public function addReceiver(User $receiver): self
    {
        if (!$this->receivers->contains($receiver)) {
            $this->receivers[] = $receiver;
        }

        return $this;
    }

    public function removeReceiver(User $receiver): self
    {
        if ($this->receivers->contains($receiver)) {
            $this->receivers->removeElement($receiver);
        }

        return $this;
    }

    public function getHasBeenConsulted(): ?bool
    {
        return $this->hasBeenConsulted;
    }

    public function setHasBeenConsulted(?bool $hasBeenConsulted): self
    {
        $this->hasBeenConsulted = $hasBeenConsulted;

        return $this;
    }


}
