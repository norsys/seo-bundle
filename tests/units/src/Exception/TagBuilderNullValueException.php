<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Units\Exception;

use Norsys\SeoBundle\Tests\Units\Test;

class TagBuilderNullValueException extends Test
{

    public function testConstruct($assert, $name, $expectedMessage)
    {
        $this
            ->assert($assert)
            ->given(
                $this->newTestedInstance($name)
            )
            ->if($message = $this->testedInstance->getMessage())
            ->then
                ->string($message)
                    ->isEqualTo($expectedMessage)
        ;
    }

    protected function testConstructDataProvider()
    {
        return [
            [
                'assert' => 'With value as an integer',
                'name'   => 'Charset',
                'expectedMessage' => 'Value for "Charset" is null.'
            ]
        ];
    }
}
