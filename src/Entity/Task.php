<?php

namespace App\Entity;

use App\Constants\Status;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"}, columnDefinition="DATETIME DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP")
     */
    private $updated_at;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Tracker", mappedBy="task", cascade={"persist", "remove"})
     */
    private $tracker;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    public function __construct()
    {
        $this->setCreatedAt();
        $this->setUpdatedAt();
        $this->setStatus(Status::PENDING);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getTracker(): ?Tracker
    {
        return $this->tracker;
    }

    public function setTracker(?Tracker $tracker): self
    {
        $this->tracker = $tracker;

        // set (or unset) the owning side of the relation if necessary
        $newTask = $tracker === null ? null : $this;
        if ($newTask !== $tracker->getTask()) {
            $tracker->setTask($newTask);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setArrayToField($data, $filter = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key) && !in_array($key, $filter) && !empty($value)) {
                $this->$key = $value;
            }
        }

        return $this;
    }
}
