<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;


    #[ORM\Column(type: 'float')]
    private $dailyCost;

    #[ORM\Column(type: 'datetime')]
    private $hireDate;

    #[ORM\ManyToOne(targetEntity: Job::class, inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    private $job;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: ProductionTime::class)]
    private $productionTimes;

    public function __construct()
    {
        $this->productionTimes = new ArrayCollection();
        $this->hireDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getDailyCost(): ?float
    {
        return $this->dailyCost;
    }

    public function setDailyCost(float $dailyCost): self
    {
        $this->dailyCost = $dailyCost;

        return $this;
    }

    public function getHireDate(): ?\DateTimeInterface
    {
        return $this->hireDate;
    }

    public function setHireDate(\DateTimeInterface $hireDate): self
    {
        $this->hireDate = $hireDate;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Collection<int, ProductionTime>
     */
    public function getProductionTimes(): Collection
    {
        return $this->productionTimes;
    }

    public function addProductionTime(ProductionTime $productionTime): self
    {
        if (!$this->productionTimes->contains($productionTime)) {
            $this->productionTimes[] = $productionTime;
            $productionTime->setEmployee($this);
        }

        return $this;
    }

    public function removeProductionTime(ProductionTime $productionTime): self
    {
        if ($this->productionTimes->removeElement($productionTime)) {
            // set the owning side to null (unless already changed)
            if ($productionTime->getEmployee() === $this) {
                $productionTime->setEmployee(null);
            }
        }

        return $this;
    }
}
