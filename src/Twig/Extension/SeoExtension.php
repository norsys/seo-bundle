<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Twig\Extension;

use Norsys\SeoBundle\Exception\TagBuilderNullValueException;
use Norsys\SeoBundle\Services\TagBuilder\Tag;
use Norsys\SeoBundle\Services\TagBuilder\TagBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * Helper class for meta/title/link rendering in Twig
 */
class SeoExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    const NODE_NAME_META = 'metas';

    /**
     * @var string
     */
    const NODE_NAME_LINK = 'links';

    /**
     * @var array
     */
    private static $tags;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var array
     */
    private $defaults;

    /**
     * @var array
     */
    private $configTitle;

    /**
     * @var array
     */
    private $data;

    /**
     * @var TagBuilderInterface
     */
    private $tagBuilder;

    /**
     *
     * @param array               $seoConfigMetas
     * @param array               $seoConfigTitle
     * @param array               $seoConfigLinks
     * @param TagBuilderInterface $tagBuilder
     */
    public function __construct(
        array $seoConfigMetas,
        array $seoConfigTitle,
        array $seoConfigLinks,
        TagBuilderInterface $tagBuilder
    ) {
        $this->defaults = $seoConfigMetas['defaults'];

        $this->configTitle = $seoConfigTitle;

        $this->initTags();

        unset($seoConfigMetas['defaults']);

        $this->data = [
            self::NODE_NAME_LINK => $seoConfigLinks,
            self::NODE_NAME_META => $seoConfigMetas,
        ];

        $this->tagBuilder = $tagBuilder;
    }

    /**
     * Init tags.
     */
    private function initTags()
    {
        if (is_array(static::$tags) === false) {
            static::$tags = [
                self::NODE_NAME_META => new Tag('meta', true),
                self::NODE_NAME_LINK => new Tag('link', true)
            ];
        }
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getFunctions() : array
    {
        return [
            // We pass "is_safe" option to have raw content output
            // @see https://twig.sensiolabs.org/doc/2.x/advanced.html#automatic-escaping
            new \Twig_SimpleFunction('seo_render_metas', [ $this, 'renderMetaTags' ], [ 'is_safe' => [ 'html'] ]),
            new \Twig_SimpleFunction('seo_render_links', [ $this, 'renderLinkTags' ], [ 'is_safe' => [ 'html'] ]),
            new \Twig_SimpleFunction('seo_render_title', [ $this, 'renderTitleTag' ], [ 'is_safe' => [ 'html'] ]),
        ];
    }

    /**
     * @param string $routeName
     * @return string
     */
    public function renderMetaTags(string $routeName = null) : string
    {
        return $this->renderTags(self::NODE_NAME_META, $routeName);
    }

    /**
     * @param string $routeName
     * @return string
     */
    public function renderLinkTags(string $routeName = null) : string
    {
        return $this->renderTags(self::NODE_NAME_LINK, $routeName);
    }

    /**
     * @param string $routeName
     * @return string
     */
    public function renderTitleTag(string $routeName = null) : string
    {
        $rawTitle = $this->configTitle['pages'][$routeName] ?? $this->configTitle['default'];

        return $this->tagBuilder->build(new Tag('title', false), [], $rawTitle);
    }

    /**
     * @param string $type
     * @param string $routeName
     * @return array
     */
    private function getData(string $type, string $routeName = null) : array
    {
        $defaults = ($type === self::NODE_NAME_META) ? $this->defaults : [];
        $routeData = $this->data[$type]['pages'][$routeName] ?? $defaults;

        return $routeData;
    }

    /**
     * @param string $type
     * @param string $routeName
     * @return string
     */
    private function renderTags(string $type, string $routeName = null) : string
    {
        $tags = [];
        $data = $this->getData($type, $routeName);
        foreach ($data as $attributes) {
            try {
                $tags[] = $this->tagBuilder->build(static::$tags[$type], $attributes);
            } catch (TagBuilderNullValueException $e) {
                // If value if null we don't create a new tag
            }
        }

        return implode("\n", $tags);
    }
}
