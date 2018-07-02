<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Services\TagBuilder;

use Norsys\SeoBundle\Exception\TagBuilderNullValueException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TranslatedTagBuilder
 */
class TranslatedTagBuilder implements TagBuilderInterface
{
    /**
     * @var TagBuilderInterface
     */
    private $tagBuilder;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * TranslatedTagBuilder constructor.
     *
     * @param TagBuilderInterface $tagBuilder
     * @param TranslatorInterface $translator
     */
    public function __construct(TagBuilderInterface $tagBuilder, TranslatorInterface $translator)
    {
        $this->tagBuilder = $tagBuilder;
        $this->translator = $translator;
    }

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
        foreach ($attributes as $name => $value) {
            if ($value === null) {
                throw new TagBuilderNullValueException($name);
            }
            $attributes[$name] = $this->translator->trans($value);
        }
        if ($content !== null) {
            $content = $this->translator->trans($content);
        }
        return $this->tagBuilder->build($tag, $attributes, $content);
    }
}
