<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Services\Translator;

use Symfony\Component\Translation\Exception\InvalidArgumentException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ConfigurableTranslator
 */
class ConfigurableTranslator implements TranslatorInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var boolean
     */
    private $enableTranslator;

    /**
     * @var string
     */
    private $defaultDomain;

    /**
     * ConfigurableTranslator constructor.
     *
     * @param TranslatorInterface $translator
     * @param boolean             $enableTranslator
     * @param string              $defaultDomain
     */
    public function __construct(
        TranslatorInterface $translator,
        bool $enableTranslator = true,
        string $defaultDomain = null
    ) {
        $this->translator = $translator;
        $this->enableTranslator = $enableTranslator;
        $this->defaultDomain = $defaultDomain;
    }

    // We start ignore coding standard because we have this error "Type hint "string" missing for $id"
    // but we can't add the type hint without break interface definition
    // @codingStandardsIgnoreStart
    /**
     * Translates the given message.
     *
     * @param string      $id         The message id (may also be an object that can be cast to string).
     * @param array       $parameters An array of parameters for the message.
     * @param string|null $domain     The domain for the message or null to use the default.
     * @param string|null $locale     The locale or null to use the default.
     *
     * @return string The translated string.
     *
     * @throws InvalidArgumentException If the locale contains invalid characters.
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if ($this->enableTranslator === false) {
            return $id;
        }

        return $this->translator->trans($id, $parameters, $this->getDomain($domain), $locale);
    }
    // @codingStandardsIgnoreEnd

    // We start ignore coding standard because we have this error "Type hint "string" missing for $id"
    // but we can't add the type hint without break interface definition
    // @codingStandardsIgnoreStart
    /**
     * Translates the given choice message by choosing a translation according to a number.
     *
     * @param string      $id         The message id (may also be an object that can be cast to string).
     * @param integer     $number     The number to use to find the indice of the message.
     * @param array       $parameters An array of parameters for the message.
     * @param string|null $domain     The domain for the message or null to use the default.
     * @param string|null $locale     The locale or null to use the default.
     *
     * @return string The translated string.
     *
     * @throws InvalidArgumentException If the locale contains invalid characters.
     *
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        if ($this->enableTranslator === false) {
            return $id;
        }

        return $this->translator->transChoice($id, $number, $parameters, $this->getDomain($domain), $locale);
    }
    // @codingStandardsIgnoreEnd

    // We start ignore coding standard because we have this error "Type hint "string" missing for $locale"
    // but we can't add the type hint without break interface definition
    // @codingStandardsIgnoreStart
    /**
     * Sets the current locale.
     *
     * @param string $locale The locale.
     *
     * @throws InvalidArgumentException If the locale contains invalid characters.
     */
    public function setLocale($locale)
    {
        $this->translator->setLocale($locale);
    }
    // @codingStandardsIgnoreEnd

    /**
     * Returns the current locale.
     *
     * @return string The locale
     */
    public function getLocale()
    {
        return $this->translator->getLocale();
    }

    /**
     * @param string|null $domain
     *
     * @return string|null
     */
    private function getDomain(string $domain = null)
    {
        if ($domain === null) {
            return $this->defaultDomain;
        }
        return $domain;
    }
}
