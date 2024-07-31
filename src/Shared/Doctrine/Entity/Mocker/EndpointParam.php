<?php

namespace App\Shared\Doctrine\Entity\Mocker;

use App\Shared\Doctrine\Repository\Mocker\EndpointParamsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EndpointParamsRepository::class)]
class EndpointParam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $name = null;

    #[ORM\Column(length: 16)]
    private string $type = 'mixed';

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $whitelist = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $regex = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $errorMessage = [400 => null, 417 => null, 418 => null];

    #[ORM\Column]
    private ?bool $required = false;

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\ManyToOne(inversedBy: 'params')]
    private ?Endpoint $scopesEndpoints = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getWhitelist(): ?array
    {
        return $this->whitelist;
    }

    public function setWhitelist(?array $whitelist): static
    {
        $this->whitelist = $whitelist;

        return $this;
    }

    public function getRegex(): ?string
    {
        return $this->regex;
    }

    public function setRegex(?string $regex): static
    {
        $this->regex = $regex;

        return $this;
    }

    public function getErrorMessage(): ?array
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?array $errorMessage): static
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): static
    {
        $this->required = $required;

        return $this;
    }

    public function isActive(): bool
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

    public function __toString(): string
    {
        return $this->name;
    }
}
