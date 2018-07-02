<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Units\Services\Translator;

require __DIR__ . '/../../../runner.php';

use Norsys\SeoBundle\Tests\Units\Test;
use Symfony\Component\Translation\TranslatorInterface;

class ConfigurableTranslator extends Test
{
    public function testClass()
    {
        $this->testedClass
            ->implements(TranslatorInterface::class);
    }

    public function testGetLocale()
    {
        $this
            ->given(
                $locale = 'fr',
                $translator = $this->newMockInstance(TranslatorInterface::class),
                $translator->getMockController()->getLocale = $locale,

                $this->newTestedInstance($translator)
            )
            ->if($result = $this->testedInstance->getLocale())
            ->then
                ->string($result)
                    ->isEqualTo($locale)

                ->mock($translator)
                    ->call('getLocale')
                        ->once()
        ;
    }

    public function testSetLocale()
    {
        $this
            ->given(
                $locale = 'fr',
                $translator = $this->newMockInstance(TranslatorInterface::class),

                $this->newTestedInstance($translator)
            )
            ->if($this->testedInstance->setLocale($locale))
            ->then
                ->mock($translator)
                    ->call('setLocale')
                        ->withArguments($locale)
                            ->once()
        ;
    }

    public function testTransWithTranslatorEnabledAndDefaultDomain()
    {
        $this
            ->given(
                $transResult = uniqid(),
                $translator = $this->newMockInstance(TranslatorInterface::class),
                $translator->getMockController()->trans = $transResult,

                $defaultDomain = uniqid(),
                $this->newTestedInstance($translator, true, $defaultDomain),

                $id = uniqid(),
                $parameters = [ uniqid() ],
                $domain = null,
                $locale = uniqid()
            )
            ->if($result = $this->testedInstance->trans($id, $parameters, $domain, $locale))
            ->then
                ->string($result)
                    ->isEqualTo($transResult)

                ->mock($translator)
                    ->call('trans')
                        ->withArguments($id, $parameters, $defaultDomain, $locale)
                            ->once()

            ->given(
                $domain = uniqid()
            )
            ->if($result = $this->testedInstance->trans($id, $parameters, $domain, $locale))
            ->then
                ->string($result)
                    ->isEqualTo($transResult)

                ->mock($translator)
                    ->call('trans')
                        ->withArguments($id, $parameters, $domain, $locale)
                            ->once()
        ;
    }

    public function testTransChoiceWithTranslatorEnabledAndDefaultDomain()
    {
        $this
            ->given(
                $transResult = uniqid(),
                $translator = $this->newMockInstance(TranslatorInterface::class),
                $translator->getMockController()->transChoice = $transResult,

                $defaultDomain = uniqid(),
                $this->newTestedInstance($translator, true, $defaultDomain),

                $id = uniqid(),
                $number = rand(0, 20),
                $parameters = [ uniqid() ],
                $domain = null,
                $locale = uniqid()
            )
            ->if($result = $this->testedInstance->transChoice($id, $number, $parameters, $domain, $locale))
            ->then
                ->string($result)
                    ->isEqualTo($transResult)

                ->mock($translator)
                    ->call('transChoice')
                        ->withArguments($id, $number, $parameters, $defaultDomain, $locale)
                            ->once()

            ->given(
                $domain = uniqid()
            )
            ->if($result = $this->testedInstance->transChoice($id, $number, $parameters, $domain, $locale))
            ->then
                ->string($result)
                    ->isEqualTo($transResult)

                ->mock($translator)
                    ->call('transChoice')
                        ->withArguments($id, $number, $parameters, $domain, $locale)
                            ->once()
        ;
    }

    public function testTransWithTranslatorDisabled()
    {
        $this
            ->given(
                $transResult = uniqid(),
                $translator = $this->newMockInstance(TranslatorInterface::class),
                $translator->getMockController()->trans = $transResult,

                $this->newTestedInstance($translator, false),

                $id = uniqid(),
                $parameters = [ uniqid() ],
                $domain = uniqid(),
                $locale = uniqid()
            )
            ->if($result = $this->testedInstance->trans($id, $parameters, $domain, $locale))
            ->then
                ->string($result)
                    ->isEqualTo($id)

                ->mock($translator)
                    ->call('trans')
                        ->never()
        ;
    }

    public function testTransChoiceWithTranslatorDisabled()
    {
        $this
            ->given(
                $transResult = uniqid(),
                $translator = $this->newMockInstance(TranslatorInterface::class),
                $translator->getMockController()->transChoice = $transResult,

                $this->newTestedInstance($translator, false),

                $id = uniqid(),
                $number = rand(0, 20),
                $parameters = [ uniqid() ],
                $domain = uniqid(),
                $locale = uniqid()
            )
            ->if($result = $this->testedInstance->transChoice($id, $number, $parameters, $domain, $locale))
            ->then
                ->string($result)
                    ->isEqualTo($id)

                ->mock($translator)
                    ->call('transChoice')
                        ->never()
        ;
    }
}
