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
    #[Assert\Range(
        min: 0,
        max: 10000000,
        notInRangeMessage: 'The value must be between {{ min }} and {{ max }}.',
    )]
    private ?int $income = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    #[Groups(['scenario:list', 'scenario:item'])]
     #[Assert\Range(
        min: 0,
        max: 10000000,
        notInRangeMessage: 'The value must be between {{ min }} and {{ max }}.',
    )]
    private ?int $outgoings = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['scenario:list', 'scenario:item'])]
     #[Assert\Range(
        min: 0,
        max: 10000000,
        notInRangeMessage: 'The value must be between {{ min }} and {{ max }}.',
    )]
    private ?int $fiTarget = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['scenario:list', 'scenario:item'])]
     #[Assert\Range(
        min: 0,
        max: 10000000,
        notInRangeMessage: 'The value must be between {{ min }} and {{ max }}.',
    )]
    private ?int $investmentAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    #[Groups(['scenario:list', 'scenario:item'])]
    #[Assert\Range(
        min: 0.0,
        max: 100.00,
        notInRangeMessage: 'The value must be between {{ min }} and {{ max }}.',
    )]
    private ?string $returnRate = null;

    #[ORM\Column]
    #[Groups(['scenario:list', 'scenario:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;
     #[Assert\Range(
        min: 0,
        max: 120,
        notInRangeMessage: 'The value must be between {{ min }} and {{ max }}.',
    )]

    #[ORM\Column(nullable: true)]
    #[Assert\Range(
        min: 0,
        max: 10000000,
        notInRangeMessage: 'The value must be between {{ min }} and {{ max }}.',
    )]
    private ?int $super = null;

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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getSuper(): ?int
    {
        return $this->super;
    }

    public function setSuper(?int $super): static
    {
        $this->super = $super;

        return $this;
    }
}
