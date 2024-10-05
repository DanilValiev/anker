<?php

namespace App\Shared\Domain\Entity\Mocker\Endpoint;

use App\Shared\Domain\Entity\Mocker\ApiScope;
use App\Shared\Domain\Entity\Mocker\Endpoint\Data\EndpointData;
use App\Shared\Domain\Entity\Mocker\Endpoint\Parameters\EndpointParam;
use App\Shared\Domain\Entity\Proxy\Proxy;
use App\Shared\Domain\Entity\Proxy\ProxyLog;
use App\Shared\Infrastructure\Doctrine\Repository\Mocker\ScopesEndpointsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScopesEndpointsRepository::class)]
class Endpoint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    private ?string $slug = null;

    #[ORM\Column]
    private string $methods = 'ANY';

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column]
    private int $sleepTime = 0;

    #[ORM\OneToMany(mappedBy: 'endpoint', targetEntity: EndpointParam::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $parameters;

    #[ORM\OneToMany(mappedBy: 'endpoint', targetEntity: EndpointData::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $data;

    #[ORM\ManyToOne(inversedBy: 'endpoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ApiScope $apiScopes = null;

    #[ORM\OneToMany(mappedBy: 'endpoint', targetEntity: Proxy::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $proxy;

    #[ORM\OneToMany(mappedBy: 'endpoint', targetEntity: ProxyLog::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $proxyLogs;

    public function __construct()
    {
        $this->parameters = new ArrayCollection();
        $this->data = new ArrayCollection();
        $this->proxy = new ArrayCollection();
        $this->proxyLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function getSleepTime(): int
    {
        return $this->sleepTime;
    }

    public function setSleepTime(?int $sleepTime): static
    {
        $this->sleepTime = $sleepTime ?? 0;

        return $this;
    }

    public function getMethods(): string
    {
        return $this->methods;
    }

    public function setMethods(string $methods): void
    {
        $this->methods = $methods;
    }

    /**
     * @return Collection<int, EndpointParam>
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    // Методы для работы с параметрами
    public function addParameter(EndpointParam $parameter): self
    {
        if (!$this->parameters->contains($parameter)) {
            $this->parameters[] = $parameter;
            $parameter->setEndpoint($this);
        }

        return $this;
    }

    public function removeParameter(EndpointParam $parameter): self
    {
        if ($this->parameters->removeElement($parameter)) {
            if ($parameter->getEndpoint() === $this) {
                $parameter->setEndpoint(null);
            }
        }

        return $this;
    }

    public function setParameters(Collection $parameters): Endpoint
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function setData(Collection $data): Endpoint
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return Collection<int, EndpointData>
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    public function addData(EndpointData $data): static
    {
        if (!$this->data->contains($data)) {
            $this->data->add($data);
            $data->setEndpoint($this);
        }

        return $this;
    }

    public function removeData(EndpointData $data): static
    {
        if ($this->data->removeElement($data)) {
            // set the owning side to null (unless already changed)
            if ($data->getEndpoint() === $this) {
                $data->setEndpoint(null);
            }
        }

        return $this;
    }

    public function getApiScopes(): ?ApiScope
    {
        return $this->apiScopes;
    }

    public function setApiScopes(?ApiScope $apiScopes): static
    {
        $this->apiScopes = $apiScopes;

        return $this;
    }

    public function getProxy(): Collection
    {
        return $this->proxy;
    }

    public function setProxy(Collection $proxy): Endpoint
    {
        $this->proxy = $proxy;

        return $this;
    }

    public function addProxy(Proxy $data): static
    {
        if (!$this->proxy->contains($data)) {
            $this->proxy->add($data);
        }

        return $this;
    }

    public function removeProxy(Proxy $data): static
    {
        $this->proxy->removeElement($data);

        return $this;
    }

    public function getProxyLogs(): Collection
    {
        return $this->proxyLogs;
    }

    public function setProxyLogs(Collection $proxyLogs): Endpoint
    {
        $this->proxyLogs = $proxyLogs;

        return $this;
    }

    public function addProxyLogs(ProxyLog $proxyLogs): static
    {
        if (!$this->proxyLogs->contains($proxyLogs)) {
            $this->proxyLogs->add($proxyLogs);
        }

        return $this;
    }

    public function removeProxyLogs(ProxyLog $data): static
    {
        $this->proxyLogs->removeElement($data);

        return $this;
    }

    public function __toString(): string
    {
        return "[{$this->methods}] /{$this->apiScopes->getSlug()}/" . $this->slug ?? 'Эндпойнт';
    }
}
