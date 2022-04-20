<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundle\Exception;

class ErrorException extends \RuntimeException
{
    protected string $customerMessage;

    public function __construct(array $errorResponse, ?\Throwable $previous = null)
    {
        parent::__construct($errorResponse['Errormessage'], (int) $errorResponse['Errorcode'], $previous);
        $this->customerMessage = $errorResponse['Customermessage'];
    }

    public function getCustomerMessage(): string
    {
        return $this->customerMessage;
    }
}