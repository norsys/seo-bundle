<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Norsys\SeoBundle\DependencyInjection\NorsysSeoExtension;

/**
 * Class NorsysSeoBundle
 */
class NorsysSeoBundle extends Bundle
{
    /**
     * @return NorsysSeoExtension
     */
    public function getExtension() : NorsysSeoExtension
    {
        return new NorsysSeoExtension();
    }
}
