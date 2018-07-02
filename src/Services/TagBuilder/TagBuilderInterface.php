<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Services\TagBuilder;

/**
 * Interface TagBuilderInterface
 */
interface TagBuilderInterface
{
    /**
     * @param TagInterface $tag
     * @param array        $attributes
     * @param string       $content
     *
     * @return string
     */
    public function build(TagInterface $tag, array $attributes = [], string $content = null): string;
}
