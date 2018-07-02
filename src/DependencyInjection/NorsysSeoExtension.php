<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension as BaseExtension;

use Norsys\SeoBundle\DependencyInjection\Config\ExtendFromDefaults as ExtendConfigFromDefaults;

/**
 * SeoBundle extension
 * Load proper SEO config in container
 **/
class NorsysSeoExtension extends BaseExtension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        $extendConfigFromDefaults = new ExtendConfigFromDefaults;
        $config = $extendConfigFromDefaults($config);

        $container->setParameter('norsys.seo.translation', $config['translation']);
        $container->setParameter('norsys.seo.translation.enabled', $config['translation']['enabled']);
        $container->setParameter('norsys.seo.translation.default_domain', $config['translation']['domain']);
        $container->setParameter('norsys.seo.metas', $config['metas']);
        $container->setParameter('norsys.seo.title', $config['title']);
        $container->setParameter('norsys.seo.links', $config['links']);
        $container->setParameter(
            'norsys.seo.rewrite.remove_trailing_slash',
            $config['rewrite']['remove_trailing_slash']
        );

        $loader = new YamlFileLoader($container, new FileLocator($this->getBundleConfigurationDir()));
        $loader->load('services.yml');
    }

    /**
     * @return string
     */
    private function getBundleConfigurationDir() : string
    {
        return sprintf('%s/../Resources/config', __DIR__);
    }
}
