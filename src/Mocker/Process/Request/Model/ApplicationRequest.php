<?php

namespace App\Mocker\Process\Request\Model;

use App\Shared\Doctrine\Entity\Mocker\ApiScope;
use App\Shared\Doctrine\Entity\Mocker\Endpoint;
use App\Shared\Doctrine\Entity\Mocker\EndpointData;

class ApplicationRequest
{
    private string $method;

    private array $userHeaders = [];

    private array $userParams = [];

    private array $userIp = [];

    private string $scopePath;

    private ?ApiScope $apiScope = null;

    private string $endpointPath;

    private ?Endpoint $endpoint = null;

    private array $params = [];

    private ?EndpointData $endpointData = null;

    private ?string $error = null;

    private ?int $errorCode = null;

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getUserHeaders(): array
    {
        return $this->userHeaders;
    }

    public function setUserHeaders(array $userHeaders): self
    {
        $this->userHeaders = $userHeaders;

        return $this;
    }

    public function getUserParams(): array
    {
        return $this->userParams;
    }

    public function setUserParams(array $userParams): self
    {
        $this->userParams = $userParams;

        return $this;
    }

    public function getUserIp(): array
    {
        return $this->userIp;
    }

    public function setUserIp(array $userIp): self
    {
        $this->userIp = $userIp;

        return $this;
    }

    public function getScopePath(): string
    {
        return $this->scopePath;
    }

    public function setScopePath(string $scopePath): self
    {
        $this->scopePath = $scopePath;

        return $this;
    }

    public function getEndpointPath(): string
    {
        return $this->endpointPath;
    }

    public function setEndpointPath(string $endpointPath): self
    {
        $this->endpointPath = $endpointPath;

        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function getApiScope(): ?ApiScope
    {
        return $this->apiScope;
    }

    public function setApiScope(?ApiScope $apiScope): self
    {
        $this->apiScope = $apiScope;

        return $this;
    }

    public function getEndpoint(): ?Endpoint
    {
        return $this->endpoint;
    }

    public function setEndpoint(?Endpoint $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getEndpointData(): ?EndpointData
    {
        return $this->endpointData;
    }

    public function setEndpointData(?EndpointData $endpointData): self
    {
        $this->endpointData = $endpointData;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): ApplicationRequest
    {
        $this->error = $error;

        return $this;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    public function setErrorCode(?int $errorCode): ApplicationRequest
    {
        $this->errorCode = $errorCode;

        return $this;
    }
}