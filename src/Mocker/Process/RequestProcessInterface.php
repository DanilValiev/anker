<?php

namespace App\Mocker\Process;

use App\Shared\Doctrine\Entity\Mocker\EndpointData;
use Symfony\Component\HttpFoundation\Request;

interface RequestProcessInterface
{
    public function process(Request $request, array $urlDetails): ?EndpointData;
}