<?php

namespace App\Modules\Proxy\Domain\Model;

trait ProxyRequestDataTrait
{
    private ?string $proxyUrl = null;

    private string $proxyParametersBagType = 'body';

    private int $proxyRequiredResponseCode = 200;

    private ?string $proxyAdditionalHeaders = null;

    private string|array|null $preparedParams = null;

    private ?ProxyResponse $proxyResponse = null;

    public function getProxyUrl(): ?string
    {
        return $this->proxyUrl;
    }

    public function setProxyUrl(?string $proxyUrl): self
    {
        $this->proxyUrl = $proxyUrl;

        return $this;
    }

    public function getProxyParametersBagType(): string
    {
        return $this->proxyParametersBagType;
    }

    public function setProxyParametersBagType(string $parametersBagType): self
    {
        $this->proxyParametersBagType = $parametersBagType;

        return $this;
    }

    public function getProxyRequiredResponseCode(): int
    {
        return $this->proxyRequiredResponseCode;
    }

    public function setProxyRequiredResponseCode(int $proxyRequiredResponseCode): self
    {
        $this->proxyRequiredResponseCode = $proxyRequiredResponseCode;

        return $this;
    }

    public function getProxyAdditionalHeaders(): ?string
    {
        return $this->proxyAdditionalHeaders;
    }

    public function setProxyAdditionalHeaders(?string $proxyAdditionalHeaders): self
    {
        $this->proxyAdditionalHeaders = $proxyAdditionalHeaders;

        return $this;
    }

    public function getPreparedParams(): array|string|null
    {
        return $this->preparedParams;
    }

    public function setPreparedParams(array|string|null $preparedParams): self
    {
        $this->preparedParams = $preparedParams;

        return $this;
    }

    public function getProxyResponse(): ?ProxyResponse
    {
        return $this->proxyResponse;
    }

    public function setProxyResponse(?ProxyResponse $proxyResponse): self
    {
        $this->proxyResponse = $proxyResponse;

        return $this;
    }
}