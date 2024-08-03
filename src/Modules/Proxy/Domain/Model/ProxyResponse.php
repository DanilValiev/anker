<?php

namespace App\Modules\Proxy\Domain\Model;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ProxyResponse
{
    private int $responseCode;

    private string $response;

    private \DateTime $requestTime;

    private array $receivedHeaders = [];

    public function __construct()
    {
        $this->requestTime = new \DateTime();
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function setResponseCode(int $responseCode): ProxyResponse
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function setResponse(string $response): ProxyResponse
    {
        $this->response = $response;

        return $this;
    }

    public function getRequestTime(): \DateTime
    {
        return $this->requestTime;
    }

    public function setRequestTime(\DateTime $requestTime): ProxyResponse
    {
        $this->requestTime = $requestTime;

        return $this;
    }

    public function getReceivedHeaders(): array
    {
        return $this->receivedHeaders;
    }

    public function setReceivedHeaders(array $receivedHeaders): ProxyResponse
    {
        $this->receivedHeaders = $receivedHeaders;

        return $this;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public static function createFromHttpResponse(ResponseInterface $response): ProxyResponse
    {
        return (new ProxyResponse())
            ->setResponse($response->getContent())
            ->setResponseCode($response->getStatusCode())
            ->setReceivedHeaders($response->getHeaders());
    }

    public static function createFromException(\Exception $response): ProxyResponse
    {
        return (new ProxyResponse())
            ->setResponse($response->getMessage())
            ->setResponseCode(500);
    }
}