<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Units\Services\TagBuilder;

use Norsys\SeoBundle\Services\TagBuilder\TagInterface;
use Norsys\SeoBundle\Tests\Units\Test;

class Tag extends Test
{
    public function testClass()
    {
        $this->testedClass
            ->implements(TagInterface::class);
    }

    public function testConstruct($assert, $name, $isSelfClosing)
    {
        $this
            ->assert($assert)
            ->given(
                $this->newTestedInstance($name, $isSelfClosing)
            )
            ->if($resultName = $this->testedInstance->getName())
            ->then
                ->string($resultName)
                    ->isEqualTo($name)

            ->if($resultIsSelfClosing = $this->testedInstance->isSelfClosing())
            ->then
                ->boolean($resultIsSelfClosing)
                    ->isEqualTo($isSelfClosing)
        ;
    }

    protected function testConstructDataProvider()
    {
        return [
            [
                'assert' => 'Test 1',
                'name' => 'title',
                'isSelfClosing' => false,
            ],
            [
                'assert' => 'Test 2',
                'name' => 'meta',
                'isSelfClosing' => true,
            ]
        ];
    }
}
