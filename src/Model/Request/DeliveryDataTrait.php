<?php declare(strict_types=1);

namespace Model\Request;

use Symfony\Component\Validator\Constraints as Assert;

trait DeliveryDataTrait
{
    #[Assert\Length(min: 1, max: 50)]
    protected ?string $shippingFirstName;

    #[Assert\Length(min: 1, max: 50)]
    protected ?string $shippingLastName;

    #[Assert\Length(min: 2, max: 50)]
    protected ?string $shippingCompany;

    #[Assert\Length(min: 2, max: 50)]
    protected ?string $shippingStreet;

    #[Assert\Regex('/^[\d\w.-_\/ ]{2,10}$/')]
    protected ?string $shippingZip;

    #[Assert\Length(min: 1, max: 50)]
    protected ?string $shippingAddressAddition;

    #[Assert\Length(min: 2, max: 50)]
    protected ?string $shippingCity;

    protected ?string $shippingState;

    #[Assert\Country]
    protected ?string $shippingCountry;

    protected function serializeDeliveryData(): array
    {
        $fields = [];
        $optional = [
            'shippingFirstName',
            'shippingLastName',
            'shippingCompany',
            'shippingStreet',
            'shippingZip',
            'shippingAddressAddition',
            'shippingCity',
            'shippingState',
            'shippingCountry',
        ];
        foreach ($optional as $prop) {
            if (!empty($this->$prop)) {
                $fields[sprintf("shipping_%s", \strtolower(explode('shipping', $prop)[1]))] = $this->$prop;
            }
        }
        return $fields;
    }
}