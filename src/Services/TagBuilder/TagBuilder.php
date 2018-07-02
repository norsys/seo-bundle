<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Services\TagBuilder;

use Norsys\SeoBundle\Exception\TagBuilderNullValueException;

/**
 * Class TagBuilder
 */
class TagBuilder implements TagBuilderInterface
{
    /**
     * @param TagInterface $tag
     * @param array        $attributes
     * @param string       $content
     *
     * @return string
     *
     * @throws TagBuilderNullValueException
     */
    public function build(TagInterface $tag, array $attributes = [], string $content = null): string
    {
        $tagName = $tag->getName();
        $result = sprintf('<%s', $tagName);
        foreach ($attributes as $name => $value) {
            if ($value === null) {
                throw new TagBuilderNullValueException($name);
            }
            $result .= ' ' . sprintf('%s="%s"', $name, $value);
        }
        if (true === $tag->isSelfClosing()) {
            $result .= ' />';
        } else {
            if ($content === null) {
                $content = '';
            }
            $result .= sprintf('>%s</%s>', $content, $tagName);
        }

        return $result;
    }
}
