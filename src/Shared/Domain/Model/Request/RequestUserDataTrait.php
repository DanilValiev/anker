<?php

namespace App\Shared\Domain\Model\Request;

trait RequestUserDataTrait
{
    private array $userHeaders = [];

    private array $userParams = [];

    private array $userIp = [];

    private array $parameters = [];

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

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }
}