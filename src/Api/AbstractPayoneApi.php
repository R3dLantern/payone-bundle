<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundle\Api;

use Scarcloud\PayoneBundle\Exception\ErrorException;
use Scarcloud\PayoneBundle\Model\PayoneRequest;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface as Exception4xx;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface as Exception3xx;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface as Exception5xx;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractPayoneApi
{
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception4xx
     * @throws Exception3xx
     * @throws Exception5xx
     * @throws ErrorException
     */
    protected function sendRequest(string $url, array $data): array
    {
        $response = $this->httpClient->request(Request::METHOD_POST, $url, [
            'header' => ['Accept' => 'application/json'],
            'body' => $data
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

    protected abstract function getBaseChannelRequest(string $request): array;

    protected function getBaseRequest(string $request): array
    {
        $allowed = [
            PayoneRequest::REQUEST_3DSCHECK,
            PayoneRequest::REQUEST_ADDRESSCHECK,
            PayoneRequest::REQUEST_AUTHORIZATION,
            PayoneRequest::REQUEST_BANKACCOUTCHECK,
            PayoneRequest::REQUEST_CAPTURE,
            PayoneRequest::REQUEST_CONSUMERSCORE,
            PayoneRequest::REQUEST_CREATEACCESS,
            PayoneRequest::REQUEST_CREDITCARDCHECK,
            PayoneRequest::REQUEST_DEBIT,
            PayoneRequest::REQUEST_GETFILE,
            PayoneRequest::REQUEST_GETINVOCE,
            PayoneRequest::REQUEST_GETUSER,
            PayoneRequest::REQUEST_MANAGEMANDATE,
            PayoneRequest::REQUEST_PREAUTHORIZATION,
            PayoneRequest::REQUEST_REFUND,
            PayoneRequest::REQUEST_UPDATEACCESS,
            PayoneRequest::REQUEST_UPDATEREMINDER,
            PayoneRequest::REQUEST_UPDATEUSER,
            PayoneRequest::REQUEST_VAUTHORIZATION,
        ];
        if (!in_array($request, $allowed)) {
            throw new \RuntimeException(sprintf(
                'Request "%s" is not a valid request method. Use one of: %s',
                $request,
                implode(', ', $allowed)
            ));
        }
        return [
            'mid' => $this->parameterBag->get('scarcloud_payone.mid'),
            'portalid' => $this->parameterBag->get('scarcloud_payone.portal_id'),
            'api_version' => $this->parameterBag->get('scarcloud_payone.api_version'),
            'mode' => $this->parameterBag->get('scarcloud_payone.mode'),
            'request' => $request
        ];
    }
}