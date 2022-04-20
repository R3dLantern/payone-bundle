<?php declare(strict_types=1);

namespace Scarcloud\PayoneBundle;

use Scarcloud\PayoneBundle\DependencyInjection\ScarcloudPayoneExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ScarcloudPayoneBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}