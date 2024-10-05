<?php

namespace App\Shared\Domain\Entity\Proxy;

use App\Shared\Domain\Entity\Mocker\Endpoint\Endpoint;
use App\Shared\Infrastructure\Doctrine\Repository\Proxy\ProxyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProxyRepository::class)]
class Proxy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $name = null;

    #[ORM\Column(length: 32)]
    private ?string $url = null;

    #[ORM\Column(length: 32)]
    private ?string $swappedUrl = null;

    #[ORM\Column(length: 16)]
    private string $method = 'GET';

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\Column]
    private int $weight = 0;

    #[ORM\Column]
    private string $parametersBagType = 'none';

    #[ORM\Column]
    private ?string $additionalHeaders = null;

    #[ORM\ManyToOne(inversedBy: 'proxy')]
    private ?Endpoint $endpoint = null;

    #[ORM\ManyToOne(targetEntity: Proxy::class)]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: true)]
    private ?Proxy $nextProxy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Proxy
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Proxy
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): Proxy
    {
        $this->url = $url;

        return $this;
    }

    public function getSwappedUrl(): ?string
    {
        return $this->swappedUrl;
    }

    public function setSwappedUrl(?string $swappedUrl): Proxy
    {
        $this->swappedUrl = $swappedUrl;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): Proxy
    {
        $this->method = $method;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): Proxy
    {
        $this->active = $active;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): Proxy
    {
        $this->weight = $weight;

        return $this;
    }

    public function getParametersBagType(): string
    {
        return $this->parametersBagType;
    }

    public function setParametersBagType(string $parametersBagType): Proxy
    {
        $this->parametersBagType = $parametersBagType;

        return $this;
    }

    public function getAdditionalHeaders(): ?string
    {
        return $this->additionalHeaders;
    }

    public function setAdditionalHeaders(?string $additionalHeaders): Proxy
    {
        $this->additionalHeaders = $additionalHeaders;

        return $this;
    }

    public function getEndpoint(): ?Endpoint
    {
        return $this->endpoint;
    }

    public function setEndpoint(?Endpoint $endpoint): Proxy
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getNextProxy(): ?Proxy
    {
        return $this->nextProxy;
    }

    public function setNextProxy(?Proxy $nextProxy): Proxy
    {
        $this->nextProxy = $nextProxy;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
