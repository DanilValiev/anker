<?php

namespace App\Shared\Domain\Entity\Mocker\Endpoint\Data;


use App\Shared\Infrastructure\Doctrine\Repository\Mocker\EndpointDataResponseVariantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EndpointDataResponseVariantRepository::class)]
#[ORM\Table(name: 'endpoint_data_response_variant')]
#[ORM\HasLifecycleCallbacks]
class ResponseVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'data', type: Types::TEXT)]
    private string $data = '{}';

    #[ORM\Column]
    private bool $active = false;

    #[ORM\ManyToOne(targetEntity: EndpointData::class, inversedBy: 'responseVariants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EndpointData $endpointData = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): ResponseVariant
    {
        $this->id = $id;
        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData($data): ResponseVariant
    {
        $this->data = $data['json'] ?? $data;
        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function formatData(): void
    {
        $decodedData = json_decode($this->data);
        if ($decodedData !== null) {
            $this->data = json_encode($decodedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): ResponseVariant
    {
        $this->active = $active;
        return $this;
    }

    public function getEndpointData(): ?EndpointData
    {
        return $this->endpointData;
    }

    public function setEndpointData(?EndpointData $endpointData): ResponseVariant
    {
        $this->endpointData = $endpointData;
        return $this;
    }

    public function __toString(): string
    {
        $data = substr($this->data, 0, 80);
        $active = $this->isActive() ? '✅' : '🚫';
        if (mb_strlen($this->data) > 40) {
            $data .= '   ...';
        }

        return "{$active} [{$this->id}] Data: {$data}";
    }
}