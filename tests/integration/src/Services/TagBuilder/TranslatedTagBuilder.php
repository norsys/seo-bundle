<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Integration\Services\TagBuilder;

require __DIR__ . '/../../../runner.php';

use Norsys\SeoBundle\Services\TagBuilder\Tag;
use Norsys\SeoBundle\Tests\Integration\IntegrationTest;
use Norsys\SeoBundle\Tests\Integration\Kernel;

class TranslatedTagBuilder extends IntegrationTest
{
    public function testBuildTag()
    {
        $this
            ->given(
                $instance = $this->createNewTestedInstance(),

                $tag = new Tag('meta', true),
                $attributes = [ 'charset' => 'UTF-8' ]
            )
            ->if($tagResult = $instance->build($tag, $attributes))
            ->then
                ->string($tagResult)
                    ->isEqualTo('<meta charset="UTF-8" />')

            ->if($attributes = [ 'name' => 'description', 'content' => 'my_page.content.description' ])
            ->and($tagResult = $instance->build($tag, $attributes))
                ->then
                    ->string($tagResult)
                        ->isEqualTo('<meta name="description" content="My translated description" />')
        ;
    }

    private function createNewTestedInstance()
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();
        return $kernel->getContainer()->get('norsys_seo.translated_tag_builder');
    }
}
