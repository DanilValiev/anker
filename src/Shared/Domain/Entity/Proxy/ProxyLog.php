<?php

namespace App\Shared\Domain\Entity\Proxy;

use App\Shared\Domain\Entity\Mocker\Endpoint\Endpoint;
use App\Shared\Domain\Model\ApplicationCommand;
use App\Shared\Infrastructure\Doctrine\Repository\Proxy\ProxyLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProxyLogRepository::class)]
class ProxyLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'proxy')]
    #[ORM\JoinColumn(nullable: false)]
    private Proxy $proxy;

    #[ORM\Column]
    private \DateTime $requestTime;

    #[ORM\Column]
    private string $response;

    #[ORM\Column]
    private int $responseCode;

    #[ORM\ManyToOne(inversedBy: 'proxyLogs')]
    private ?Endpoint $endpoint = null;

    public function __construct()
    {
        $this->requestTime = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProxy(): Proxy
    {
        return $this->proxy;
    }

    public function setProxy(Proxy $proxy): ProxyLog
    {
        $this->proxy = $proxy;

        return $this;
    }
    public function getRequestTime(): \DateTime
    {
        return $this->requestTime;
    }

    public function setRequestTime(\DateTime $requestTime): ProxyLog
    {
        $this->requestTime = $requestTime;

        return $this;
    }

    public function getResponse(): string
    {
        return json_encode(json_decode($this->response, true), JSON_PRETTY_PRINT);
    }

    public function setResponse(string $response): ProxyLog
    {
        $this->response = $response;

        return $this;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function setResponseCode(int $responseCode): ProxyLog
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    public function getEndpoint(): ?Endpoint
    {
        return $this->endpoint;
    }

    public function setEndpoint(?Endpoint $endpoint): ProxyLog
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public static function createFromApplicationRequest(ApplicationCommand $applicationCommand): ProxyLog
    {
        $proxyResponse = $applicationCommand->getProxyResponse();

        return (new ProxyLog())
            ->setProxy($applicationCommand->getProxy())
            ->setResponse($proxyResponse->getResponse())
            ->setResponseCode($proxyResponse->getResponseCode());
    }

    public function __toString(): string
    {
        return "Log [$this->id] $this->proxy->getName()";
    }
}
