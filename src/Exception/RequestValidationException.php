<?php declare(strict_types=1);

namespace Exception;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RequestValidationException extends \RuntimeException
{
    private array $errors = [];

    public function __construct(ConstraintViolationListInterface $violationList, ?\Throwable $previous = null)
    {
        parent::__construct(
            'Given request data is invalid.',
            $violationList->count(),
            $previous
        );
        /** @var ConstraintViolationInterface $item */
        foreach ($violationList as $item) {
            $this->errors[] = sprintf(
                'Field "%s": %s',
                $item->getPropertyPath(),
                $item->getMessage()
            );
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}