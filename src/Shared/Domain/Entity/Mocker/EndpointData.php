<?php

namespace App\Shared\Domain\Entity\Mocker;

use App\Shared\Infrastructure\Doctrine\Repository\Mocker\EndpointDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EndpointDataRepository::class)]
class EndpointData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $expression = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $data;

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\Column]
    private int $statusCode = 200;

    #[ORM\ManyToOne(inversedBy: 'data')]
    private ?Endpoint $scopesEndpoints = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpression(): ?string
    {
        return $this->expression;
    }

    public function setExpression(?string $expression): static
    {
        $this->expression = $expression;

        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getScopesEndpoints(): ?Endpoint
    {
        return $this->scopesEndpoints;
    }

    public function setScopesEndpoints(?Endpoint $scopesEndpoints): static
    {
        $this->scopesEndpoints = $scopesEndpoints;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function __toString(): string
    {
        return "[{$this->getId()}] Exp: {{$this->expression}}";
    }
}
