<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundle\Api;

use Exception\RequestValidationException;
use Scarcloud\PayoneBundle\Exception\ErrorException;
use Scarcloud\PayoneBundle\Model\PayoneRequest;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface as Exception4xx;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface as Exception3xx;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface as Exception5xx;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Channel Server API
 * @author Leon Willens <lvilents@gmail.com>
 */
class PayoneApi
{
    public const API_URL = 'https://api.pay1.de/post-gateway/';

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly HttpClientInterface $httpClient,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @param PayoneRequest $payoneRequest
     * @return array
     * @throws Exception3xx
     * @throws Exception4xx
     * @throws Exception5xx
     * @throws TransportExceptionInterface
     */
    public function sendRequest(PayoneRequest $payoneRequest): array
    {
        $fullRequest = $this->setDefaults($payoneRequest);
        $violations = $this->validator->validate($fullRequest);
        if ($violations->count() > 0) {
            throw new RequestValidationException($violations);
        }

        $response = $this->httpClient->request(Request::METHOD_POST, self::API_URL, [
            'header' => ['Accept' => 'application/json'],
            'body' => serialize($fullRequest)
        ]);

        $plainTextMimes = ['text/plain; charset=UTF-8', 'text/plain; charset=ISO-8859-1'];
        $responsePayload = in_array($response->getHeaders()['Content-Type'], $plainTextMimes)
            ? $this->parseResponse($response)
            : json_decode($response->getContent(), true)
        ;

        if ($responsePayload['Status'] === 'ERROR') {
            throw new ErrorException($responsePayload);
        }

        return $responsePayload;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception4xx
     * @throws Exception3xx
     * @throws Exception5xx
     */
    protected function parseResponse(ResponseInterface $response): array
    {
        $result = [];
        $explode = explode('\n', $response->getContent());
        foreach ($explode as $item) {
            $keyValue = explode('=', $item);
            if (trim($keyValue[0]) != "") {
                if (count($keyValue) == 2) {
                    $result[$keyValue[0]] = trim($keyValue[1]);
                } else {
                    $key = $keyValue[0];
                    unset($keyValue[0]);
                    $value = implode("=", $keyValue);
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }

    protected function setDefaults(PayoneRequest $request): PayoneRequest
    {
        return $request
            ->setMid($this->parameterBag->get('scarcloud_payone.mid'))
            ->setPortalId($this->parameterBag->get('scarcloud_payone.portal_id'))
            ->setKey($this->parameterBag->get('scarcloud_payone.portal_key'))
            ->setApiVersion($this->parameterBag->get('scarcloud_payone.api_version'))
            ->setMode($this->parameterBag->get('scarcloud_payone.mode'))
            ->setEncoding($this->parameterBag->get('scarcloud_payone.encoding'))
        ;
    }
}