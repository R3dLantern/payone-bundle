<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundleModel\Request;

use Scarcloud\PayoneBundle\Model\PayoneRequest;
use Scarcloud\PayoneBundle\Validator\Numeric;
use Symfony\Component\Validator\Constraints as Assert;

class Preauthorization extends PayoneRequest
{
    #[Assert\NotBlank]
    #[Numeric(minLength: 5, maxLength: 6)]
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

    #[Assert\NotBlank]
    #[Assert\Regex('/^[\d\w.-_\/]{1,20}$/')]
    protected ?string $reference = null;

    #[Assert\NotBlank]
    #[Assert\Currency]
    protected ?string $currency = null;

    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual(-1999999999)]
    #[Assert\LessThanOrEqual(1999999999)]
    protected ?int $amount = null;

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

    public function __serialize(): array
    {
        return array_merge(
            parent::__serialize(),
            [
                'aid' => $this->aid,
                'clearingtype' => $this->clearingType,
                'reference' => $this->reference,
                'currency' => $this->currency,
                'amount' => $this->amount
            ]
        );
    }
}