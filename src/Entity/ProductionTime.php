<?php

namespace App\Entity;

use App\Repository\ProductionTimeRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductionTimeRepository::class)]
class ProductionTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'productionTimes')]
    #[ORM\JoinColumn(nullable: false)]
    private $employee;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'productionTimes')]
    #[ORM\JoinColumn(nullable: false)]
    private $project;

    #[ORM\Column(type: 'string', length: 255)]
    private $time;

    #[ORM\Column(type: 'datetime')]
    private $entryAt;


    public function __construct()
    {
        $this->entryAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getEntryAt(): ?\DateTimeInterface
    {
        return $this->entryAt;
    }

    public function setEntryAt(\DateTimeInterface $entryAt): self
    {
        $this->entryAt = $entryAt;

        return $this;
    }
}
