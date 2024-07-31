<?php

namespace App\Mocker\Process\Providers;

use App\Mocker\Process\Request\Model\ApplicationRequest;

interface ProviderInterface
{
    public function get(ApplicationRequest $request);
}