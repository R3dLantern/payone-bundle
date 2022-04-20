<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundle\Api;

use Scarcloud\PayoneBundle\Exception\ErrorException;
use Scarcloud\PayoneBundle\Model\PayoneRequest;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface as Exception4xx;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface as Exception3xx;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface as Exception5xx;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Channel Server API
 * @author Leon Willens <lvilents@gmail.com>
 */
class PayoneServerApi extends AbstractPayoneApi
{
    public const API_URL = 'https://api.pay1.de/post-gateway/';

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        parent::__construct($httpClient, $parameterBag);
    }

    /**
     * Initiates a payment reservation.
     * @see https://docs.payone.com/pages/releaseview.action?pageId=1213939
     * @return array
     * @throws Exception3xx
     * @throws Exception4xx
     * @throws Exception5xx
     * @throws TransportExceptionInterface
     * @throws ErrorException
     */
    public function preauthorize(): array
    {
        return $this->sendRequest(self::API_URL, array_merge(
            $this->getBaseChannelRequest(PayoneRequest::REQUEST_PREAUTHORIZATION),
            [

            ]
        ));
    }

    protected function getBaseChannelRequest(string $request): array
    {
        return array_merge(
            $this->getBaseRequest($request),
            [
                'key' => md5($this->parameterBag->get('scarcloud_payone.portal_key'))
            ]
        );
    }
}