<?php

namespace App\Entity;

use App\Repository\ScenarioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScenarioRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Scenario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(nullable: true)]
    private ?int $income = null;

    #[ORM\Column(nullable: true)]
    private ?int $outgoings = null;

    #[ORM\Column(nullable: true)]
    private ?int $fiTarget = null;

    #[ORM\Column(nullable: true)]
    private ?int $investmentAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $returnRate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getIncome(): ?string
    {
        return $this->income;
    }

    public function setIncome(?string $income): static
    {
        $this->income = $income;

        return $this;
    }

    public function getOutgoings(): ?int
    {
        return $this->outgoings;
    }

    public function setOutgoings(?int $outgoings): static
    {
        $this->outgoings = $outgoings;

        return $this;
    }

    public function getFiTarget(): ?int
    {
        return $this->fiTarget;
    }

    public function setFiTarget(?int $fiTarget): static
    {
        $this->fiTarget = $fiTarget;

        return $this;
    }

    public function getInvestmentAmount(): ?int
    {
        return $this->investmentAmount;
    }

    public function setInvestmentAmount(?int $investmentAmount): static
    {
        $this->investmentAmount = $investmentAmount;

        return $this;
    }

    public function getReturnRate(): ?string
    {
        return $this->returnRate;
    }

    public function setReturnRate(?string $returnRate): static
    {
        $this->returnRate = $returnRate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
