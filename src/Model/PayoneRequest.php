<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundle\Model;

class PayoneRequest
{
    public const CLEARINGTYPE_DEBIT_PAYMENT = 'elv';
    public const CLEARINGTYPE_CREDIT_CARD = 'cc';
    public const CLEARINGTYPE_INVOICE = 'rec';
    public const CLEARINGTYPE_CASH_ON_DELIVERY = 'cod';
    public const CLEARINGTYPE_PREPAYMENT = 'vor';
    public const CLEARINGTYPE_ONLINE_BANK_TRANSFER = 'sb';
    public const CLEARINGTYPE_EWALLET = 'wlt';
    public const CLEARINGTYPE_FINANCING = 'fnc';
    public const CLEARINGTYPE_CASH_OR_HYBRID = 'csh';

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
}