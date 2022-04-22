<?php declare(strict_types=1);

namespace Model\Request;

use Symfony\Component\Validator\Constraints as Assert;

trait SecureInvoiceTrait
{
    #[Assert\Choice(['POV'])]
    protected ?string $clearingSubType = null;

    protected function serializeSecureInvoice(): array
    {
        return empty($this->clearingSubType) ? [] : ['clearingsubtype' => $this->clearingSubType];
    }
}