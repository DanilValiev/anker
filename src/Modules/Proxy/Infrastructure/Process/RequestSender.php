<?php

namespace App\Modules\Proxy\Infrastructure\Process;

use App\Modules\Proxy\Domain\Model\ProxyResponse;
use App\Modules\Proxy\Domain\Process\RequestSenderInterface;
use App\Shared\Domain\Model\ApplicationCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RequestSender implements RequestSenderInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * @throws \Exception
     * @throws TransportExceptionInterface
     */
    public function send(ApplicationCommand $request): ProxyResponse
    {
        $client = HttpClient::create();
        [$options, $queryParams] = $this->buildAdditionalRequestData($request);

        $this->logger->info('Start proxy request',
            ['url' => $request->getProxy()->getUrl(), 'options' => $options, 'queryParams' => $queryParams]);

        $response = $client->request($request->getMethod(), $request->getProxyUrl() . $queryParams, $options);

        $this->logger->info('Finish proxy request', ['url' => $request->getProxy()->getUrl()]);

        return ProxyResponse::createFromHttpResponse($response);
    }

    private function buildAdditionalRequestData(ApplicationCommand $request): array
    {
        $preparedParams = $request->getPreparedParams();
        $queryParams = '';
        $options = ['headers' => $request->getUserHeaders()];

        if (is_string($preparedParams)) {
            $queryParams = $preparedParams;
        } else if (is_array($preparedParams)) {
            $options[] = $preparedParams;
        }

        if ($request->getProxyAdditionalHeaders()) {
            $options['headers'] = array_merge(
                $options['headers'], json_decode($request->getProxyAdditionalHeaders(), true)
            );
        }

        return [$options, $queryParams];
    }
}