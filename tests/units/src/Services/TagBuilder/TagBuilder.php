<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Units\Services\TagBuilder;

use Norsys\SeoBundle\Exception\TagBuilderNullValueException;
use Norsys\SeoBundle\Services\TagBuilder\TagBuilderInterface;
use Norsys\SeoBundle\Services\TagBuilder\TagInterface;
use Norsys\SeoBundle\Tests\Units\Test;

use \mock\Norsys\SeoBundle\Services\TagBuilder\TagInterface as MockOfTag;

class TagBuilder extends Test
{
    public function testClass()
    {
        $this->testedClass
            ->implements(TagBuilderInterface::class);
    }

    public function testBuild($assert, TagInterface $tag, array $attributes, string $content = null, string $expectedResult)
    {
        $this
            ->assert($assert)
            ->given(
                $this->newTestedInstance()
            )
            ->if($result = $this->testedInstance->build($tag, $attributes, $content))
            ->then
                ->string($result)
                    ->isEqualTo($expectedResult)
        ;
    }

    protected function testBuildDataProvider()
    {
        return [
            [
                'assert' => 'Test 1 : Meta charset UTF-8',
                'tag' => $this->createMockOfTagInterface('meta', true),
                'attributes' => [ 'charset' => 'UTF-8' ],
                'content' => null,
                'expectedResult' => '<meta charset="UTF-8" />'
            ],
            [
                'assert' => 'Test 2 : Meta description',
                'tag' => $this->createMockOfTagInterface('meta', true),
                'attributes' => [ 'name' => 'description', 'content' => 'foo' ],
                'content' => 'not displayed',
                'expectedResult' => '<meta name="description" content="foo" />'
            ],
            [
                'assert' => 'Test 3 : Img not self close and no content',
                'tag' => $this->createMockOfTagInterface('img', false),
                'attributes' => [ 'url' => 'foo', 'alt' => 'bar' ],
                'content' => null,
                'expectedResult' => '<img url="foo" alt="bar"></img>'

            ],
            [
                'assert' => 'Test 4 : Span not self close',
                'tag' => $this->createMockOfTagInterface('span', false),
                'attributes' => [ 'class' => 'foo' ],
                'content' => 'text',
                'expectedResult' => '<span class="foo">text</span>'
            ],
            [
                'assert' => 'Test 5 : Title not self close without attributes',
                'tag' => $this->createMockOfTagInterface('span', false),
                'attributes' => [],
                'content' => 'text',
                'expectedResult' => '<span>text</span>'
            ]
        ];
    }

    public function testBuildWithNotValidAttributes()
    {
        $this
            ->given(
                $tag = $this->createMockOfTagInterface('meta', true),
                $attributes = [ 'name' => 'description', 'content' => null ],
                $this->newTestedInstance()
            )
            ->if($callable = function () use ($tag, $attributes) {
                $this->testedInstance->build($tag, $attributes);
            })
            ->then
                ->exception($callable)
                    ->isInstanceOf(TagBuilderNullValueException::class)
        ;
    }

    protected function createMockOfTagInterface(string $name, bool $isSelfClosing)
    {
        $mock = new MockOfTag();
        $mock->getMockController()->getName = $name;
        $mock->getMockController()->isSelfClosing = $isSelfClosing;
        return $mock;
    }
}
