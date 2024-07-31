<?php

namespace App\Mocker\Process\Providers\ParametersProvider;

use App\Mocker\Exceptions\Variations\Parameters\InvalidParamsRegexException;
use App\Mocker\Exceptions\Variations\Parameters\InvalidParamsTypeException;
use App\Mocker\Exceptions\Variations\Parameters\ParamsNotFoundException;
use App\Mocker\Exceptions\Variations\Parameters\ParamsValueIsNotFoundInWhitelistException;
use App\Mocker\Process\Providers\ProviderInterface;
use App\Mocker\Process\Request\Model\ApplicationRequest;
use App\Mocker\Process\Request\Validator\RequestParametersValidator;

class ParametersProvider implements ProviderInterface
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
    public function get(ApplicationRequest $request): array
    {
        $params = $request->getEndpoint()->getParams();
        $requestParams = $request->getParams();
        $validatedParams = [];

        foreach ($params as $param) {
            $name = $param->getName();
            if ($param->isActive() && $param->isRequired() && array_key_exists($name, $requestParams)) {
                $value = $requestParams[$name];
                $this->parametersValidator->validate($param, $value);

                $validatedParams[$name] = $value;
            } else if ($param->isActive() && $param->isRequired() && !array_key_exists($name, $requestParams)) {
                throw new ParamsNotFoundException($errorMessage[417] ?? "Required params {{$name}} not found");
            }
        }

        return $validatedParams;
    }

}