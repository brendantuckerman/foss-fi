<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ScenarioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ScenarioRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'scenario:item']),
        new GetCollection(normalizationContext: ['groups' => 'scenario:list'])
    ],
    order: ['label' => 'DESC', 'createdAt' => 'DESC'],
    paginationEnabled: true
)]
class Scenario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Groups(['scenario:list', 'scenario:item'])]
    private ?string $label = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    #[Groups(['scenario:list', 'scenario:item'])]
    private ?int $income = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    #[Groups(['scenario:list', 'scenario:item'])]
    private ?int $outgoings = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['scenario:list', 'scenario:item'])]
    private ?int $fiTarget = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['scenario:list', 'scenario:item'])]
    private ?int $investmentAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    #[Groups(['scenario:list', 'scenario:item'])]
    private ?string $returnRate = null;

    #[ORM\Column]
    #[Groups(['scenario:list', 'scenario:item'])]
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
