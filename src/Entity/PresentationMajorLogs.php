<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use App\Repository\PresentationMajorLogsRepository;

/**
 * @ORM\Entity(repositoryClass=PresentationMajorLogsRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class PresentationMajorLogs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=PPBasic::class, inversedBy="presentationMajorLogs", cascade={"persist", "remove"})
     */
    private $presentation;

    /**
     * The journal containing presentation major logs
     * structure of a row : [entity type; event type; entity id; event date]
     * one row content example : ["news", "new", 223, 22 july 1984]
     * 
     * @ORM\Column(type="json", nullable=true)
     */
    private $majorLogs = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    
    public function __construct()
    {

        $this->updatedAt = new \DateTime('now');

    }



    //Allow to update presentation major logs journal

    public function updateLogs (?PPBasic $presentation, $entityType, $eventType, $entityId, EntityManagerInterface $manager) {

            
        if (!$presentation->getPresentationMajorLogs()){

            $presentationMajorLogs = new PresentationMajorLogs();
        }

        else {

            $presentationMajorLogs = $presentation->getPresentationMajorLogs();
        }

        $logs=$presentationMajorLogs->getMajorLogs();

        $logs[]=
        
            [
                'entityType'=> $entityType, 
                'eventType'=> $eventType, 
                'entityId'=> $entityId, 
                'date'=> new \DateTime('now')
            ]
        ;

        $presentationMajorLogs->setMajorLogs($logs);

        if (!$presentation->getPresentationMajorLogs()){
            
            $presentationMajorLogs->setPresentation($presentation);

            $manager->persist($presentation);

        }

        $manager->persist($presentationMajorLogs);

        $manager->flush();

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

    public function getPresentation(): ?PPBasic
    {
        return $this->presentation;
    }

    public function setPresentation(?PPBasic $presentation): self
    {
        $this->presentation = $presentation;

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

    public function getMajorLogs(): ?array
    {
        return $this->majorLogs;
    }

    public function setMajorLogs(?array $majorLogs): self
    {
        $this->majorLogs = $majorLogs;

        return $this;
    }
}
