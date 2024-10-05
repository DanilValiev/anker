<?php

namespace App\Shared\Domain\Entity\Mocker\Endpoint\Data;

use App\Shared\Domain\Entity\Mocker\Endpoint\Endpoint;
use App\Shared\Infrastructure\Doctrine\Repository\Mocker\EndpointDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: EndpointDataRepository::class)]
class EndpointData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $expression = null;

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\Column]
    private int $statusCode = 200;

    #[ORM\ManyToOne(inversedBy: 'data')]
    private ?Endpoint $endpoint = null;

    #[ORM\OneToMany(mappedBy: 'endpointData', targetEntity: EndpointDataResponseVariant::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $responseVariants;

    public function __construct()
    {
        $this->responseVariants = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): EndpointData
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validateActiveVariants(ExecutionContextInterface $context): void
    {
        $activeVariants = array_filter($this->responseVariants->toArray(), function($variant) {
            return $variant->isActive();
        });

        if (count($activeVariants) !== 1) {
            $context->buildViolation('Только один вариант ответа может быть активен.')
                ->atPath('responseVariants')
                ->addViolation();
        }
    }

    public function getResponseVariants(): Collection
    {
        return $this->responseVariants;
    }

    public function addResponseVariant(EndpointDataResponseVariant $responseVariant): self
    {
        if (!$this->responseVariants->contains($responseVariant)) {
            $this->responseVariants[] = $responseVariant;
            $responseVariant->setEndpointData($this);
        }

        return $this;
    }

    public function removeResponseVariant(EndpointDataResponseVariant $responseVariant): self
    {
        if ($this->responseVariants->removeElement($responseVariant)) {
            // set the owning side to null (unless already changed)
            if ($responseVariant->getEndpointData() === $this) {
                $responseVariant->setEndpointData(null);
            }
        }

        return $this;
    }

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
        /** @var EndpointDataResponseVariant $responseVariant */
        foreach ($this->responseVariants as $responseVariant) {
            if ($responseVariant->isActive()) {
                return $responseVariant->getData();
            }
        }

        return $this->data;
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

    public function getEndpoint(): ?Endpoint
    {
        return $this->endpoint;
    }

    public function setEndpoint(?Endpoint $endpoint): static
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(?int $statusCode): static
    {
        $this->statusCode = $statusCode ?? 200;

        return $this;
    }

    public function __toString(): string
    {
        return "[{$this->getId()}] {$this->name}";
    }
}
