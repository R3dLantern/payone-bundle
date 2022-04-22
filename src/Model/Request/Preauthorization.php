<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundleModel\Request;

use Model\Request\DeliveryDataTrait;
use Model\Request\PersonalDataTrait;
use Model\Request\SecureInvoiceTrait;
use Scarcloud\PayoneBundle\Model\PayoneRequest;
use Scarcloud\PayoneBundle\Validator\Numeric;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Preauthorization extends PayoneRequest
{
    #[Assert\NotBlank, Numeric(minLength: 5, maxLength: 6)]
    protected ?string $aid = null;

    #[Assert\NotBlank]
    #[Assert\Choice([
        self::CLEARINGTYPE_CASH_ON_DELIVERY,
        self::CLEARINGTYPE_CASH_OR_HYBRID,
        self::CLEARINGTYPE_CREDIT_CARD,
        self::CLEARINGTYPE_DEBIT_PAYMENT,
        self::CLEARINGTYPE_EWALLET,
        self::CLEARINGTYPE_FINANCING,
        self::CLEARINGTYPE_INVOICE,
        self::CLEARINGTYPE_ONLINE_BANK_TRANSFER,
        self::CLEARINGTYPE_PREPAYMENT,
    ])]
    protected ?string $clearingType = null;

    #[Assert\NotBlank, Assert\Regex('/^[\d\w.-_\/]{1,20}$/')]
    protected ?string $reference = null;

    #[Assert\NotBlank, Assert\Currency]
    protected ?string $currency = null;

    #[Assert\NotNull, Assert\GreaterThanOrEqual(-1999999999), Assert\LessThanOrEqual(1999999999)]
    protected ?int $amount = null;

    #[Assert\Length(min: 1, max: 255)]
    protected ?string $param = null;

    #[Assert\Length(min: 1, max: 81)]
    protected ?string $narrativeText = null;

    #[Assert\Choice([
        self::RECURRENCE_NONE,
        self::RECURRENCE_ONECLICK,
        self::RECURRENCE_RECURRING,
        self::RECURRENCE_INSTALLMENT
    ])]
    protected ?string $recurrence = null;

    protected ?bool $customerPresent = null;

    public function __construct()
    {
        parent::__construct(self::REQUEST_PREAUTHORIZATION);
    }

    public function setAid(string $aid): self
    {
        $this->aid = $aid;
        return $this;
    }

    public function setClearingType(string $clearingType): self
    {
        $this->clearingType = $clearingType;
        return $this;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function setParam(?string $param): self
    {
        $this->param = $param;
        return $this;
    }

    public function setNarrativeText(?string $narrativeText): self
    {
        $this->narrativeText = $narrativeText;
        return $this;
    }

    public function setRecurrence(?string $recurrence): self
    {
        $this->recurrence = $recurrence;
        return $this;
    }

    public function setCustomerPresent(?bool $customerPresent): self
    {
        $this->customerPresent = $customerPresent;
        return $this;
    }

    use SecureInvoiceTrait;

    use PersonalDataTrait;

    use DeliveryDataTrait;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        if ($this->clearingType === PayoneRequest::CLEARINGTYPE_INVOICE && $this->clearingSubType !== 'POV') {
            $context
                ->buildViolation(
                    'Preauthorization requests must be configured with PAYONE secure purchase on invoice.'
                )
                ->atPath('clearingSubType')
                ->addViolation()
            ;
        }

        if (empty($this->lastName) && empty($this->company)) {
            $context
                ->buildViolation(
                    'Either the last name with an optional first name or a company name must be specified.'
                )
                ->atPath('lastName')
                ->addViolation()
            ;
        }

        $stateCountries = ['US', 'CA', 'CN', 'JP', 'MX', 'BR', 'AR', 'ID', 'TH', 'IN'];
        if (in_array($this->country, $stateCountries) && empty($this->state)) {
            $context
                ->buildViolation('State information is needed for country "{{ code }}"')
                ->setParameter('{{ code }}', $this->country)
                ->atPath('state')
                ->addViolation()
            ;
        } elseif (!in_array($this->country, $stateCountries) && !empty($this->state)) {
            $context
                ->buildViolation('State information must be omitted for country "{{ code }}"')
                ->setParameter('{{ code }}', $this->country)
                ->atPath('state')
                ->addViolation()
            ;
        }

        if (in_array($this->shippingCountry, $stateCountries) && empty($this->shippingState)) {
            $context
                ->buildViolation('State information is needed for shipping country "{{ code }}"')
                ->setParameter('{{ code }}', $this->country)
                ->atPath('state')
                ->addViolation()
            ;
        } elseif (!in_array($this->shippingCountry, $stateCountries) && !empty($this->shippingState)) {
            $context
                ->buildViolation('State information must be omitted for shipping country "{{ code }}"')
                ->setParameter('{{ code }}', $this->country)
                ->atPath('state')
                ->addViolation()
            ;
        }
    }

    public function __serialize(): array
    {
        $fields = [
            'aid' => $this->aid,
            'clearingtype' => $this->clearingType,
            'reference' => $this->reference,
            'currency' => $this->currency,
            'amount' => $this->amount,
        ];
        if (!empty($this->param)) {
            $fields['param'] = $this->param;
        }
        if (!empty($this->narrativeText)) {
            $fields['narrative_text'] = $this->narrativeText;
        }
        if (!empty($this->recurrence)) {
            $fields['recurrence'] = $this->recurrence;
        }
        if (null !== $this->customerPresent) {
            $fields['customer_is_present'] = $this->customerPresent ? 'yes' : 'no';
        }
        return array_merge(
            parent::__serialize(),
            $fields,
            $this->serializeSecureInvoice(),
            $this->serializePersonalData(),
            $this->serializeDeliveryData()
        );
    }
}