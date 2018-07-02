<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Services\TagBuilder;

/**
 * Interface TagInterface
 */
interface TagInterface
{
    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return boolean
     */
    public function isSelfClosing() : bool;
}
