<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrackerRepository")
 */
class Tracker
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_time;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default"=0})
     */
    private $total_seconds;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"}, columnDefinition="DATETIME DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $task_id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Task", inversedBy="tracker", cascade={"persist", "remove"})
     */
    private $task;

    public function __construct()
    {
        $this->setCreatedAt();
        $this->setUpdatedAt();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getTotalSeconds(): ?int
    {
        return $this->total_seconds;
    }

    public function setTotalSeconds(?int $total_seconds): self
    {
        $this->total_seconds = $total_seconds;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(): self
    {
        if(!$this->created_at){
            $this->created_at = new \DateTime();
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(): self
    {
        $this->updated_at = new \DateTime();

        return $this;
    }

    public function getTaskId(): ?int
    {
        return $this->task_id;
    }

    public function setTaskId(?int $task_id): self
    {
        $this->task_id = $task_id;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

}
