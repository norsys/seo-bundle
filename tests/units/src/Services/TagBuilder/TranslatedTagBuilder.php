<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Units\Services\TagBuilder;

use Norsys\SeoBundle\Exception\TagBuilderNullValueException;
use Norsys\SeoBundle\Services\TagBuilder\TagBuilderInterface;
use Norsys\SeoBundle\Services\TagBuilder\TagInterface;
use Norsys\SeoBundle\Tests\Units\Test;
use Symfony\Component\Translation\TranslatorInterface;

class TranslatedTagBuilder extends Test
{
    public function testClass()
    {
        $this->testedClass
            ->implements(TagBuilderInterface::class);
    }

    public function testBuild()
    {
        $this
            ->given(
                $expectedResult = uniqid(),
                $tagBuilder = $this->createMockOfTagBuilder($expectedResult),
                $translator = $this->createMockOfTranslator('prefix'),

                $this->newTestedInstance($tagBuilder, $translator),

                $tag = $this->newMockInstance(TagInterface::class),
                $value = 'bar',
                $attributes = [ 'foo' => $value ],
                $content = 'text',

                $expectedAttributes = [ 'foo' => 'prefix-bar' ],
                $expectedContent = 'prefix-text'
            )
            ->if($result = $this->testedInstance->build($tag, $attributes, $content))
            ->then
                ->string($result)
                    ->isEqualTo($expectedResult)

                ->mock($translator)
                    ->call('trans')
                        ->withArguments($value)
                            ->once()

                ->mock($translator)
                    ->call('trans')
                        ->withArguments($content)
                            ->once()

                ->mock($tagBuilder)
                    ->call('build')
                        ->withArguments($tag, $expectedAttributes, $expectedContent)
                            ->once()

            ->assert('If value is null, we should throw a TagBuilderNullValueException')
            ->given(
                $attributes = [ 'foo' => null ]
            )
            ->if($callable = function () use ($tag, $attributes, $content) {
                $this->testedInstance->build($tag, $attributes, $content);
            })
            ->then
                ->exception($callable)
                    ->isInstanceOf(TagBuilderNullValueException::class)

                ->mock($translator)
                    ->call('trans')
                        ->never()

                ->mock($tagBuilder)
                    ->call('build')
                        ->never()
        ;
    }

    private function createMockOfTagBuilder($result)
    {
        $mock = $this->newMockInstance(TagBuilderInterface::class);
        $mock->getMockController()->build = $result;
        return $mock;
    }

    private function createMockOfTranslator($prefix)
    {
        $mock = $this->newMockInstance(TranslatorInterface::class);
        $mock->getMockController()->trans = function ($value) use ($prefix) {
            return $prefix . '-' . $value;
        };
        return $mock;
    }
}
