<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundle\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Numeric extends Constraint
{
    public string $message = 'Value "{{ value }}" must be a numeric string';
    public string $minMessage = 'Value "{{ value }}" must have at least {{ limit }} digits';
    public string $maxMessage = 'Value "{{ value }}" must not have more than {{ limit }} digits';
    public ?int $minLength;
    public ?int $maxLength;

    public function __construct(
        ?string $message = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?int $minLength = null,
        ?int $maxLength = null,
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
        $this->message = $message ?? $this->message;
        $this->minMessage = $minMessage ?? $this->minMessage;
        $this->maxMessage = $maxMessage ?? $this->maxMessage;
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }
}
