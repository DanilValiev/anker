<?php

namespace App\Modules\Mocker\Infrastructure\Process\Validator;

use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\InvalidParamsRegexException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\InvalidParamsTypeException;
use App\Modules\Mocker\Domain\Process\Exceptions\Parameters\ParamsValueIsNotFoundInWhitelistException;
use App\Shared\Domain\Entity\Mocker\EndpointParam;

class RequestParametersValidator
{
    /**
     * @throws ParamsValueIsNotFoundInWhitelistException
     * @throws InvalidParamsRegexException
     * @throws InvalidParamsTypeException
     */
    public function validate(EndpointParam $param, string $value): void
    {
        $this->validateParamType($param, $value);
        $this->validateParamRegex($param, $value);
        $this->validateParamWhitelist($param, $value);
    }

    /**
     * @throws InvalidParamsTypeException
     */
    private function validateParamType($param, $value): void
    {
        if (($param->getType() === 'int' && !is_numeric($value))
            || ($param->getType() === 'bool' && ($value != 'true' && $value != 'false'))
            || ($param->getType() === 'string' && !is_string($value))) {
            throw new InvalidParamsTypeException($errorMessage[417] ?? "Params type invalid {{$param->getType()}} required");
        }
    }

    /**
     * @throws InvalidParamsRegexException
     */
    private function validateParamRegex($param, $value): void
    {
        $regex = $param->getRegex();
        if ($regex && !preg_match($regex, $value)) {
            throw new InvalidParamsRegexException($errorMessage[418] ?? "Params struct invalid, required regex {{$regex}}");
        }
    }

    /**
     * @throws ParamsValueIsNotFoundInWhitelistException
     */
    private function validateParamWhitelist($param, $value): void
    {
        $whitelist = $param->getWhitelist();
        if ($whitelist && !in_array($value, $whitelist)) {
            $implodedWhitelist = implode(', ', $param->getWhitelist());
            throw new ParamsValueIsNotFoundInWhitelistException($errorMessage[419] ?? "Params value is not found in whitelist {$implodedWhitelist}");
        }
    }
}