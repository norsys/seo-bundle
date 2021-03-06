<?php
declare(strict_types=1);

namespace Norsys\SeoBundle\Tests\Integration;

use Norsys\SeoBundle\NorsysSeoBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class Kernel
 *
 * @package Norsys\SeoBundle\Tests\Integration
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @var string $varPath
     */
    private $varPath;

    /**
     * Kernel constructor.
     *
     * @param string  $environment
     * @param boolean $debug
     */
    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);
        $this->rootDir = __DIR__.'/../app/';
        $this->varPath = __DIR__.'/../var/';
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new NorsysSeoBundle(),
        ];
    }

    /**
     * Add or import routes into your application.
     *
     *     $routes->import('config/routing.yml');
     *     $routes->add('/admin', 'AppBundle:Admin:dashboard', 'admin_dashboard');
     *
     * @param RouteCollectionBuilder $routes
     *
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->import($this->getProjectDir().'/src/Resources/config/routing.yml');
    }

    /**
     * Configures the container.
     *
     * You can register extensions:
     *
     * $c->loadFromExtension('framework', array(
     *     'secret' => '%secret%'
     * ));
     *
     * Or services:
     *
     * $c->register('halloween', 'FooBundle\HalloweenProvider');
     *
     * Or parameters:
     *
     * $c->setParameter('halloween', 'lot of fun');
     *
     * @param ContainerBuilder $c
     * @param LoaderInterface  $loader
     *
     * @throws \Exception
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/../etc/config.yml');
        $loader->load($this->getProjectDir().'/src/Resources/config/services.yml');
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return $this->varPath.'/logs';
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->varPath.'/cache';
    }
}
