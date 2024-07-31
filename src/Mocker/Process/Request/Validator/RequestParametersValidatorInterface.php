<?php

namespace App\Mocker\Process\Request\Validator;

use App\Shared\Doctrine\Entity\Mocker\EndpointParam;

interface RequestParametersValidatorInterface
{
    public function validate(EndpointParam $param, string $value): void;
}