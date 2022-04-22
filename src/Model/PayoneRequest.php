<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundle\Model;

use Scarcloud\PayoneBundle\Validator\Numeric;
use Symfony\Component\Validator\Constraints as Assert;

abstract class PayoneRequest
{
    public const REQUEST_PREAUTHORIZATION = 'preauthorization';
    public const REQUEST_AUTHORIZATION = 'authorization';
    public const REQUEST_CAPTURE = 'capture';
    public const REQUEST_DEBIT = 'debit';
    public const REQUEST_REFUND = 'refund';
    public const REQUEST_CREATEACCESS = 'createaccess';
    public const REQUEST_VAUTHORIZATION = 'vauthorization';
    public const REQUEST_MANAGEMANDATE = 'managemandate';
    public const REQUEST_GETFILE = 'getfile';
    public const REQUEST_GETINVOCE = 'getinvoice';
    public const REQUEST_UPDATEUSER = 'updateuser';
    public const REQUEST_GETUSER = 'getuser';
    public const REQUEST_UPDATEACCESS = 'updateaccess';
    public const REQUEST_UPDATEREMINDER = 'updatereminder';
    public const REQUEST_CREDITCARDCHECK = 'creditcardcheck';
    public const REQUEST_BANKACCOUTCHECK = 'bankaccountcheck';
    public const REQUEST_3DSCHECK = '3dscheck';
    public const REQUEST_ADDRESSCHECK = 'addresscheck';
    public const REQUEST_CONSUMERSCORE = 'consumerscore';

    public const API_VERSION_3_8 = '3.8';
    public const API_VERSION_3_9 = '3.9';
    public const API_VERSION_3_10 = '3.10';
    public const API_VERSION_3_11 = '3.11';

    public const ENCODING_ISO_8859_1 = 'ISO-8859-1';
    public const ENCODING_UTF_8 = 'UTF-8';

    public const MODE_TEST = 'test';
    public const MODE_LIVE = 'live';

    public const CLEARINGTYPE_DEBIT_PAYMENT = 'elv';
    public const CLEARINGTYPE_CREDIT_CARD = 'cc';
    public const CLEARINGTYPE_INVOICE = 'rec';
    public const CLEARINGTYPE_CASH_ON_DELIVERY = 'cod';
    public const CLEARINGTYPE_PREPAYMENT = 'vor';
    public const CLEARINGTYPE_ONLINE_BANK_TRANSFER = 'sb';
    public const CLEARINGTYPE_EWALLET = 'wlt';
    public const CLEARINGTYPE_FINANCING = 'fnc';
    public const CLEARINGTYPE_CASH_OR_HYBRID = 'csh';

    public const RECURRENCE_NONE = 'none';
    public const RECURRENCE_ONECLICK = 'oneclick';
    public const RECURRENCE_RECURRING = 'recurring';
    public const RECURRENCE_INSTALLMENT = 'installment';

    public const BUSINESSRELATION_B2C = 'b2c';
    public const BUSINESSRELATION_B2B = 'b2b';

    public const GENDER_MALE = 'm';
    public const GENDER_FEMALE = 'f';
    public const GENDER_DIVERSE = 'd';

    #[Assert\NotBlank]
    #[Numeric(minLength: 5, maxLength: 6)]
    protected ?string $mid = null;

    #[Assert\NotBlank]
    #[Numeric(minLength: 7, maxLength: 7)]
    protected ?string $portalId = null;

    #[Assert\NotBlank]
    protected ?string $key = null;

    #[Assert\NotBlank]
    #[Assert\Choice([
        self::MODE_TEST,
        self::MODE_LIVE
    ])]
    protected ?string $mode = null;

    #[Assert\NotBlank]
    #[Assert\Choice([
        self::REQUEST_PREAUTHORIZATION,
        self::REQUEST_AUTHORIZATION,
        self::REQUEST_CAPTURE,
        self::REQUEST_DEBIT,
        self::REQUEST_REFUND,
        self::REQUEST_CREATEACCESS,
        self::REQUEST_VAUTHORIZATION,
        self::REQUEST_MANAGEMANDATE,
        self::REQUEST_GETFILE,
        self::REQUEST_GETINVOCE,
        self::REQUEST_UPDATEUSER,
        self::REQUEST_GETUSER,
        self::REQUEST_UPDATEACCESS,
        self::REQUEST_UPDATEREMINDER,
        self::REQUEST_CREDITCARDCHECK,
        self::REQUEST_BANKACCOUTCHECK,
        self::REQUEST_3DSCHECK,
        self::REQUEST_ADDRESSCHECK,
        self::REQUEST_CONSUMERSCORE,
    ])]
    protected string $request;

    #[Assert\Choice([
        self::API_VERSION_3_8,
        self::API_VERSION_3_9,
        self::API_VERSION_3_10,
        self::API_VERSION_3_11,
    ])]
    protected string $apiVersion = self::API_VERSION_3_8;

    #[Assert\Choice([
        self::ENCODING_ISO_8859_1,
        self::ENCODING_UTF_8
    ])]
    protected string $encoding = self::ENCODING_ISO_8859_1;

    public function __construct(string $request)
    {
        $this->request = $request;
    }

    public function setMid(string $mid): self
    {
        $this->mid = $mid;
        return $this;
    }

    public function setPortalId(string $portalId): self
    {
        $this->portalId = $portalId;
        return $this;
    }

    public function setKey(string $key): self
    {
        $this->key = md5($key);
        return $this;
    }

    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    public function setEncoding(string $encoding): self
    {
        $this->encoding = $encoding;
        return $this;
    }

    public function __serialize(): array
    {
        return [
            'mid' => $this->mid,
            'portalid' => $this->portalId,
            'key' => $this->key,
            'api_version' => $this->apiVersion,
            'mode' => $this->mode,
            'encoding' => $this->encoding,
            'request' => $this->request
        ];
    }
}