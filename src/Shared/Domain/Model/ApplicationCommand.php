<?php

namespace App\Shared\Domain\Model;

use App\Modules\Proxy\Domain\Model\ProxyRequestDataTrait;
use App\Shared\Domain\Entity\Mocker\ApiScope;
use App\Shared\Domain\Entity\Mocker\Endpoint\Data\EndpointData;
use App\Shared\Domain\Entity\Mocker\Endpoint\Endpoint;
use App\Shared\Domain\Entity\Proxy\Proxy;
use App\Shared\Domain\Model\Request\RequestUserDataTrait;
use Symfony\Component\HttpFoundation\Request;

class ApplicationCommand
{
    use RequestUserDataTrait;
    use ProxyRequestDataTrait;

    private string $method;

    private string $scopePath;

    private ?ApiScope $apiScope = null;

    private string $endpointPath;

    private ?Endpoint $endpoint = null;

    private ?EndpointData $endpointData = null;

    private ?string $error = null;

    private ?int $errorCode = null;

    private ?Proxy $proxy = null;

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

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

    public function setError(?string $error): ApplicationCommand
    {
        $this->error = $error;

        return $this;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    public function setErrorCode(?int $errorCode): ApplicationCommand
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getProxy(): ?Proxy
    {
        return $this->proxy;
    }

    public function setProxy(?Proxy $proxy): ApplicationCommand
    {
        $this->proxy = $proxy;

        return $this;
    }

    public static function create(Request $request, array $urlDetails): ApplicationCommand
    {
        $body = json_decode($request->getContent(), true);
        if ($request->getMethod() == 'GET') {
            $params = array_merge($_GET, $body ?? []);
        } else {
            $params = array_merge($_POST, $_GET, $body ?? []);
        }

        return (new ApplicationCommand())
            ->setMethod($request->getMethod())
            ->setUserParams($params)
            ->setUserHeaders($request->headers->all())
            ->setUserIp($request->getClientIps())
            ->setScopePath($urlDetails['scope'])
            ->setEndpointPath($urlDetails['endpoint'])
            ->setParameters($params)
            ;
    }
}