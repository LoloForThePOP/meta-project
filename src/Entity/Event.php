<?php

namespace App\Entity;

use DateTime;
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

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    

    // transforms a chunked date or incomplete date to a complete datetime. Exemple : an event is set at year 2022. We transform this to datetime 01 - 01 - 2022. This allows us, for example, to easily order incomplete dates (or to throw notifications to users even with incomplete dates).

    // remark: $monthSpan is an integer wich represents a month or a trimester or a semester : a value between 1-12 is for a month; 13 relates to first trimester; 36 to second trimester; 69 to third trimester; and so on); or a semester (16 stands for fisrt semester; 612 stands for second semester).

    // if no year is provided, we do not construct a date
    // if no month or month span is provided, we set date month to 1 by default
    // if no day is provided, we set date day to 1 by default

    public function toVirtualDate (?int $year, ?int $monthSpan, ?int $day) {

        // without a year we do not construct a date
        if ($year == NULL) 
        {
            return NULL;

        }

        else
        {

            $month = 1;

            if ($monthSpan >= 1 && $monthSpan <= 12 ) 
            {
                $month= $monthSpan;
            }

            else
            {
                switch ($monthSpan) 
                {
                
                    case 13:
                        $month = 1;
                        break;

                    case 36:
                        $month = 3;
                        break;

                    case 69:
                        $month = 6;
                        break;

                    case 912:
                        $month = 9;
                        break;

                    case 16:
                        $month = 1;
                        break;

                    case 612:
                        $month = 6;
                        break;
                    
                    default:
                        # code...
                        break;

                    }
        
            }

            if($day==NULL)
            {
                 $day=1;
            }
            
            //die(dump($day));

            $timestamp = mktime(1, 1, 1, $month, $day, $year);

            $date = new DateTime();

            $date->setTimestamp($timestamp);

            return $date;

        }

    }


    public function toStringDate_Fr (?int $year, ?int $monthSpan, ?int $day) {

        if ($year == NULL && $monthSpan == NULL && $day == NULL) {
            return null;
        }

        elseif ($year !== NULL && $monthSpan == NULL && $day == NULL) {

            return "Année ".$year;

        }

        else {

            //output string monthSpan (exemple : janvier; 1er trimestre; etc)

            $stringMonthSpan="";

            switch ($monthSpan) {

                case 1: 
                    
                    $stringMonthSpan = "janvier";
                    break;

                case 2: 
                    
                    $stringMonthSpan = "février";
                    break;

                case 3: 
                    
                    $stringMonthSpan = "mars";
                    break;

                case 4: 
                    
                    $stringMonthSpan = "avril";
                    break;

                case 5: 
                    
                    $stringMonthSpan = "mai";
                    break;

                case 6: 
                    
                    $stringMonthSpan = "juin";
                    break;

                case 7: 
                    
                    $stringMonthSpan = "juillet";
                    break;

                case 8: 
                    
                    $stringMonthSpan = "août";
                    break;

                case 9: 
                    
                    $stringMonthSpan = "septembre";
                    break;

                case 10: 
                    
                    $stringMonthSpan = "octobre";
                    break;

                case 11: 
                    
                    $stringMonthSpan = "novembre";
                    break;

                case 12: 
                    
                    $stringMonthSpan = "décembre";
                    break;
            
                case 13:
                    $stringMonthSpan = "1er trimestre";
                    break;

                case 36:
                    $stringMonthSpan = "2ème trimestre";
                    break;

                case 69:
                    $stringMonthSpan = "3ème trimestre";
                    break;

                case 912:
                    $stringMonthSpan = "4ème trimestre";
                    break;

                case 16:
                    $stringMonthSpan = "1er semestre";
                    break;

                case 612:
                    $stringMonthSpan = "2ème semestre";
                    break;
                
                default:
                    # code...
                    break;

            }

            return $day.' '.$stringMonthSpan.' '.$year;

        }

    }

    //return begin date string (example : year 2027; 1 semester 2028; etc)

    public function beginDateStr() {

        return $this->toStringDate_Fr ($this->getBeginYear(), $this->getBeginMonth(), $this->getBeginDay());
    }

    //return ending date string (example : year 2027; 1 semester 2028; etc)

    public function endDateStr() {

        return $this->toStringDate_Fr ($this->getEndYear(), $this->getEndMonth(), $this->getEndDay());
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }



}
