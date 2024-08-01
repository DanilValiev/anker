<?php

namespace App\Shared\Doctrine\Entity\Mocker;

use App\Shared\Doctrine\Repository\Mocker\ProcessLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProcessLogRepository::class)]
class ProcessLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $method;

    #[ORM\Column(type: Types::JSON)]
    private array $incomingHeaders = [];

    #[ORM\Column(type: Types::JSON)]
    private array $incomingParams = [];

    #[ORM\Column(type: Types::JSON)]
    private array $userIps = [];

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ApiScope $scope;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Endpoint $endpoint;

    #[ORM\Column]
    private \DateTime $requestTime;

    #[ORM\Column]
    private string $response;

    #[ORM\Column]
    private int $responseCode;

    public function __construct()
    {
        $this->requestTime = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): ProcessLog
    {
        $this->method = $method;

        return $this;
    }

    public function getIncomingHeaders(): string
    {
        return json_encode($this->incomingHeaders, JSON_PRETTY_PRINT);
    }

    public function setIncomingHeaders(array $incomingHeaders): ProcessLog
    {
        $this->incomingHeaders = $incomingHeaders;

        return $this;
    }

    public function getIncomingParams(): string
    {
        return json_encode($this->incomingParams, JSON_PRETTY_PRINT);
    }

    public function setIncomingParams(array $incomingParams): ProcessLog
    {
        $this->incomingParams = $incomingParams;

        return $this;
    }

    public function getUserIps(): string
    {
        return json_encode($this->userIps, JSON_PRETTY_PRINT);
    }

    public function setUserIps(array $userIps): ProcessLog
    {
        $this->userIps = $userIps;

        return $this;
    }

    public function getScope(): ApiScope
    {
        return $this->scope;
    }

    public function setScope(ApiScope $scope): ProcessLog
    {
        $this->scope = $scope;

        return $this;
    }

    public function getEndpoint(): Endpoint
    {
        return $this->endpoint;
    }

    public function setEndpoint(Endpoint $endpoint): ProcessLog
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getRequestTime(): \DateTime
    {
        return $this->requestTime;
    }

    public function setRequestTime(\DateTime $requestTime): ProcessLog
    {
        $this->requestTime = $requestTime;

        return $this;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function setResponse(string $response): ProcessLog
    {
        $this->response = $response;

        return $this;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function setResponseCode(int $responseCode): ProcessLog
    {
        $this->responseCode = $responseCode;

        return $this;
    }
}
