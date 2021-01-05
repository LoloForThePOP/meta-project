<?php

namespace App\Entity;

use App\Repository\PPMajorLogsRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\EntityManagerInterface;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * @ORM\Entity(repositoryClass=PPMajorLogsRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class PPMajorLogs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=PPBasic::class, inversedBy="majorLogs", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $presentation;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $logs = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;



    
    public function __construct()
    {

        $this->updatedAt = new \DateTime('now');

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



    

    //Allow to update presentation major logs journal

    public function updateLogs (?PPBasic $presentation, $entityType, $eventType, $entityId, EntityManagerInterface $manager) {

            
        if (!$presentation->getMajorLogs()){

            $presentationMajorLogs = new PPMajorLogs();
        }

        else {

            $presentationMajorLogs = $presentation->getMajorLogs();
        }

        $logs=$presentationMajorLogs->getLogs();

        $logs[]=
        
            [
                'entityType'=> $entityType, 
                'eventType'=> $eventType, 
                'entityId'=> $entityId, 
                'date'=> new \DateTime('now')
            ]
        ;

        $presentationMajorLogs->setLogs($logs);

        if (!$presentation->getMajorLogs()){
            
            $presentationMajorLogs->setPresentation($presentation);

            $manager->persist($presentation);

        }

        $manager->persist($presentationMajorLogs);

        $manager->flush();

    }




















    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPresentation(): ?PPBasic
    {
        return $this->presentation;
    }

    public function setPresentation(PPBasic $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getLogs(): ?array
    {
        return $this->logs;
    }

    public function setLogs(?array $logs): self
    {
        $this->logs = $logs;

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
}
