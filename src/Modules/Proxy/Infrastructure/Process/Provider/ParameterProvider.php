<?php

namespace App\Modules\Proxy\Infrastructure\Process\Provider;

use App\Modules\Proxy\Domain\Process\Provider\ParameterProviderInterface;
use App\Shared\Domain\Model\ApplicationCommand;

class ParameterProvider implements ParameterProviderInterface
{
    public function get(ApplicationCommand $request): array|string|null
    {
        return match ($request->getProxy()->getParametersBagType()) {
            'body' => $this->getInBody($request),
            'form' => $this->getInForm($request),
            'query' => $this->getInQuery($request),
            default => null
        };
    }

    private function getInBody(ApplicationCommand $request): array
    {
        return ['json' => $request->getParameters()];
    }

    private function getInForm(ApplicationCommand $request): array
    {
        return ['body' => $request->getParameters()];
    }

    private function getInQuery(ApplicationCommand $request): string
    {
        $queryParameters = '';
        foreach ($request->getParameters() as $key => $value) {
            $queryParameters .= ($queryParameters == '') ? '?' : '&';
            $queryParameters .= "$key=$value";
        }

        return $queryParameters;
    }
}