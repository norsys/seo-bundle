<?php

namespace Norsys\SeoBundle\Tests\Integration;

use mageekguy\atoum;
use mageekguy\atoum\adapter;
use mageekguy\atoum\annotations;
use mageekguy\atoum\asserter;
use mageekguy\atoum\analyzer;
use mageekguy\atoum\test;
use mageekguy\atoum\tools;

class IntegrationTest extends atoum
{
    public function __construct(
        adapter $adapter = null,
        annotations\extractor $annotationExtractor = null,
        asserter\generator $asserterGenerator = null,
        test\assertion\manager $assertionManager = null,
        \closure $reflectionClassFactory = null,
        \closure $phpExtensionFactory = null,
        tools\variable\analyzer $analyzer = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory,
            $phpExtensionFactory,
            $analyzer
        );
        $this->setTestNamespace('#(?:^|\\\)tests?\\\integration?\\\#i');
    }

    protected function createAndBootKernel(string $environment = 'test', bool $debug = true)
    {
        $kernel = new Kernel($environment, $debug);
        $kernel->boot();
        return $kernel;
    }
}
