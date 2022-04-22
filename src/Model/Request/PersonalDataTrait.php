<?php declare(strict_types=1);

namespace Model\Request;

use Scarcloud\PayoneBundle\Model\PayoneRequest;
use Scarcloud\PayoneBundle\Validator\Numeric;
use Symfony\Component\Validator\Constraints as Assert;

trait PersonalDataTrait
{
    #[Assert\Regex('/^[\d\w.-_\/]{1,20}$/')]
    protected ?string $customerId = null;

    #[Numeric(minLength: 6, maxLength: 12)]
    protected ?string $userId = null;

    #[Assert\Choice([
        PayoneRequest::BUSINESSRELATION_B2B,
        PayoneRequest::BUSINESSRELATION_B2C
    ])]
    protected ?string $businessRelation = null;

    #[Assert\Length(min: 1, max: 10)]
    protected ?string $salutation = null;

    #[Assert\Length(min: 1, max: 20)]
    protected ?string $title = null;

    protected ?string $firstName = null;

    protected ?string $lastName = null;

    protected ?string $company = null;

    #[Assert\Length(min: 1, max: 50)]
    protected ?string $street = null;

    #[Assert\Length(min: 1, max: 50)]
    protected ?string $addressAddition = null;

    #[Assert\Regex('/^[\d\w.-_\/ ]{2,10}$/')]
    protected ?string $zip = null;

    #[Assert\Length(min: 1, max: 50)]
    protected ?string $city = null;

    #[Assert\NotBlank, Assert\Country]
    protected ?string $country = null;

    protected ?string $state = null;

    #[Assert\Email]
    protected ?string $email = null;

    #[Assert\Length(min: 1, max: 30)]
    protected ?string $telephoneNumber = null;

    protected ?\DateTimeInterface $birthday = null;

    #[Assert\Language]
    protected ?string $language = null;

    #[Assert\Length(min: 1, max: 50)]
    protected ?string $vatId = null;

    #[Assert\Choice([
        PayoneRequest::GENDER_MALE,
        PayoneRequest::GENDER_FEMALE,
        PayoneRequest::GENDER_DIVERSE
    ])]
    protected ?string $gender = null;

    #[Assert\Regex('/^[\d\w\+.-\/\(\)]{1,32}$/')]
    protected ?string $personalId = null;

    #[Assert\Ip]
    protected ?string $ip = null;

    public function setCustomerId(?string $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setBusinessRelation(?string $businessRelation): self
    {
        $this->businessRelation = $businessRelation;
        return $this;
    }

    public function setSalutation(?string $salutation): self
    {
        $this->salutation = $salutation;
        return $this;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;
        return $this;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function setAddressAddition(?string $addressAddition): self
    {
        $this->addressAddition = $addressAddition;
        return $this;
    }

    public function setZip(?string $zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setTelephoneNumber(?string $telephoneNumber): self
    {
        $this->telephoneNumber = $telephoneNumber;
        return $this;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;
        return $this;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function setVatId(?string $vatId): self
    {
        $this->vatId = $vatId;
        return $this;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function setPersonalId(?string $personalId): self
    {
        $this->personalId = $personalId;
        return $this;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    protected function serializePersonalData(): array
    {
        $fields = [
            'country' => $this->country
        ];
        $optional = [
            'customerId',
            'userId',
            'businessRelation',
            'salutation',
            'title',
            'firstName',
            'lastName',
            'company',
            'street',
            'addressAddition',
            'zip',
            'city',
            'state',
            'email',
            'telephoneNumber',
            'birthday',
            'language',
            'vatId',
            'gender',
            'personalId',
            'ip'
        ];
        foreach ($optional as $prop) {
            if (!empty($this->$prop)) {
                $fields[\strtolower($prop)] = $this->$prop instanceof \DateTimeInterface
                    ? $this->$prop->format('Ymd')
                    : $this->$prop
                ;
            }
        }
        return $fields;
    }
}