<?php

namespace App\Mocker\Process\Request\Factory;

use App\Mocker\Process\Request\Model\ApplicationRequest;
use Symfony\Component\HttpFoundation\Request;

class ApplicationRequestFactory implements ApplicationRequestFactoryInterface
{
    public function create(Request $request, array $urlDetails): ApplicationRequest
    {
        $body = json_decode($request->getContent(), true);
        if ($request->getMethod() == 'GET') {
            $params = array_merge($_GET, $body ?? []);
        } else {
            $params = array_merge($_POST, $_GET, $body ?? []);
        }

        return (new ApplicationRequest())
            ->setMethod($request->getMethod())
            ->setScopePath($urlDetails['scope'])
            ->setEndpointPath($urlDetails['endpoint'])
            ->setParams($params)
        ;
    }
}