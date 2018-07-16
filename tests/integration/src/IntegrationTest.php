<?php

namespace Norsys\SeoBundle\Tests\Integration;

use mageekguy\atoum;
use mageekguy\atoum\adapter;
use mageekguy\atoum\annotations;
use mageekguy\atoum\asserter;
use mageekguy\atoum\analyzer;
use mageekguy\atoum\test;
use mageekguy\atoum\tools;

/**
 * Class IntegrationTest
 *
 * @package Norsys\SeoBundle\Tests\Integration
 */
class IntegrationTest extends atoum
{
    /**
     * IntegrationTest constructor.
     *
     * @param adapter|null                 $adapter
     * @param annotations\extractor|null   $annotationExtractor
     * @param asserter\generator|null      $asserterGenerator
     * @param test\assertion\manager|null  $assertionManager
     * @param \closure|null                $reflectionClassFactory
     * @param \closure|null                $phpExtensionFactory
     * @param tools\variable\analyzer|null $analyzer
     */
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

    /**
     * @param string  $environment
     * @param boolean $debug
     *
     * @return Kernel
     */
    protected function createAndBootKernel(string $environment = 'test', bool $debug = true)
    {
        $kernel = new Kernel($environment, $debug);
        $kernel->boot();
        return $kernel;
    }
}
