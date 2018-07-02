<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Integration\Twig\Extension;

require __DIR__ . '/../../../runner.php';

use Norsys\SeoBundle\Tests\Integration\IntegrationTest;
use Norsys\SeoBundle\Tests\Integration\Kernel;

class SeoExtension extends IntegrationTest
{
    public function testMetaTagsWithDefaultData()
    {
        $this
            ->given(
                $instance = $this->createNewTestedInstance(),
                $results = '<meta charset="UTF-8" />
<meta name="description" content="defaults.description" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" />'
            )
            ->if($tags = $instance->renderMetaTags('defaults'))
            ->then
                ->string($tags)
                    ->isEqualTo($results)
        ;
    }

    public function testMetaTagsWithRoute()
    {
        $this
            ->given(
                $instance = $this->createNewTestedInstance(),
                $results = '<meta charset="UTF-8" />
<meta name="description" content="Home page of my super web site" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" />'
            )
            ->if($tags = $instance->renderMetaTags('home'))
            ->then
                ->string($tags)
                    ->isEqualTo($results)
        ;
    }

    public function testMetaTagsWithNullDescription()
    {
        $this
            ->given(
                $instance = $this->createNewTestedInstance(),
                $results = '<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" />'
            )
            ->if($tags = $instance->renderMetaTags('empty'))
            ->then
                ->string($tags)
                    ->isEqualTo($results)
        ;
    }

    public function testLinkTagsWithDefaultData()
    {
        $this
            ->given(
                $instance = $this->createNewTestedInstance(),
                $results = '<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />'
            )
            ->if($tags = $instance->renderLinkTags('defaults'))
            ->then
                ->string($tags)
                    ->isEqualTo($results)
        ;
    }

    public function testLinkTagsWithRoute()
    {
        $this
            ->given(
                $instance = $this->createNewTestedInstance(),
                $results = '<link rel="alternate" href="http://super-site.fr/" hreflang="fr-fr" />'
            )
            ->if($tags = $instance->renderLinkTags('home'))
            ->then
                ->string($tags)
                    ->isEqualTo($results)
        ;
    }

    public function testTitleTagsWithDefaultData()
    {
        $this
            ->given(
                $instance = $this->createNewTestedInstance(),
                $results = '<title>My super web site</title>'
            )
            ->if($tag = $instance->renderTitleTag('defaults'))
            ->then
                ->string($tag)
                    ->isEqualTo($results)
        ;
    }

    public function testTitleTagsWithRoute()
    {
        $this
            ->given(
                $instance = $this->createNewTestedInstance(),
                $results = '<title>Home | My super web site</title>'
            )
            ->if($tag = $instance->renderTitleTag('home'))
            ->then
                ->string($tag)
                    ->isEqualTo($results)
        ;
    }

    private function createNewTestedInstance()
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();
        return $kernel->getContainer()->get('norsys_seo.twig.extension.seo');
    }
}
