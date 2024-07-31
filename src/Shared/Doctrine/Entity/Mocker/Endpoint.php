<?php

namespace App\Shared\Doctrine\Entity\Mocker;

use App\Shared\Doctrine\Repository\Mocker\ScopesEndpointsRepository;
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

    #[ORM\OneToMany(mappedBy: 'scopesEndpoints', targetEntity: EndpointParam::class, cascade: ['persist'])]
    private Collection $params;

    #[ORM\OneToMany(mappedBy: 'scopesEndpoints', targetEntity: EndpointData::class, cascade: ['persist'])]
    private Collection $data;

    #[ORM\ManyToOne(inversedBy: 'endpoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ApiScope $apiScopes = null;

    public function __construct()
    {
        $this->params = new ArrayCollection();
        $this->data = new ArrayCollection();
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

    public function setSleepTime(int $sleepTime): static
    {
        $this->sleepTime = $sleepTime;

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
    public function getParams(): Collection
    {
        return $this->params;
    }

    public function addParam(EndpointParam $param): static
    {
        if (!$this->params->contains($param)) {
            $this->params->add($param);
            $param->setScopesEndpoints($this);
        }

        return $this;
    }

    public function removeParam(EndpointParam $param): static
    {
        if ($this->params->removeElement($param)) {
            // set the owning side to null (unless already changed)
            if ($param->getScopesEndpoints() === $this) {
                $param->setScopesEndpoints(null);
            }
        }

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
            $data->setScopesEndpoints($this);
        }

        return $this;
    }

    public function removeData(EndpointData $data): static
    {
        if ($this->data->removeElement($data)) {
            // set the owning side to null (unless already changed)
            if ($data->getScopesEndpoints() === $this) {
                $data->setScopesEndpoints(null);
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

    public function __toString(): string
    {
        return "[{$this->methods}] /{$this->apiScopes->getSlug()}/" . $this->slug ?? 'Эндпойнт';
    }
}
