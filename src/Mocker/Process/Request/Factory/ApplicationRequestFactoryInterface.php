<?php

namespace App\Mocker\Process\Request\Factory;

use App\Mocker\Process\Request\Model\ApplicationRequest;
use Symfony\Component\HttpFoundation\Request;

interface ApplicationRequestFactoryInterface
{
    function create(Request $request, array $urlDetails): ApplicationRequest;
}