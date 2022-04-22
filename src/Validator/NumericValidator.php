<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NumericValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Numeric) {
            throw new UnexpectedTypeException($constraint, Numeric::class);
        }
        if (empty($value)) {
            return;
        }
        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }
        if (!is_numeric($value)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation()
            ;
            return;
        }
        if (!empty($constraint->minLength) && strlen($value) < $constraint->minLength) {
            $this->context
                ->buildViolation($constraint->minMessage)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ limit }}', $constraint->minLength)
                ->addViolation()
            ;
            return;
        }
        if (!empty($constraint->maxLength) && strlen($value) > $constraint->maxLength) {
            $this->context
                ->buildViolation($constraint->maxMessage)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ limit }}', $constraint->maxMessage)
                ->addViolation()
            ;
        }
    }
}