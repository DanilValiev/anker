<?php

namespace App\Modules\Mocker\Infrastructure\Process\Provider;

use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\InvalidParamsRegexException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\InvalidParamsTypeException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\ParamsNotFoundException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\ParamsValueIsNotFoundInWhitelistException;
use App\Modules\Mocker\Domain\Process\Provider\ParametersProviderInterface;
use App\Modules\Mocker\Infrastructure\Process\Validator\RequestParametersValidator;
use App\Shared\Domain\Model\ApplicationCommand;

class ParametersProvider implements ParametersProviderInterface
{
    public function __construct(
        private readonly RequestParametersValidator $parametersValidator
    )
    {
    }

    /**
     * @throws ParamsNotFoundException
     * @throws InvalidParamsTypeException
     * @throws InvalidParamsRegexException
     * @throws ParamsValueIsNotFoundInWhitelistException
     */
    public function get(ApplicationCommand $request): array
    {
        $params = $request->getEndpoint()->getParameters();
        $requestParams = $request->getParameters();
        $validatedParams = [];

        foreach ($params as $param) {
            $name = $param->getName();
            if ($param->isActive() && $param->isRequired() && array_key_exists($name, $requestParams)) {
                $value = $requestParams[$name];
                $this->parametersValidator->validate($param, $value);

                $validatedParams[$name] = $value;
            } else if ($param->isActive() && $param->isRequired() && !array_key_exists($name, $requestParams)) {
                throw new ParamsNotFoundException($errorMessage[417] ?? "Required parameters {{$name}} not found");
            }
        }

        return $validatedParams;
    }

}