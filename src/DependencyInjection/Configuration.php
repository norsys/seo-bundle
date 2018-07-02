<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var string
    **/
    const BUNDLE_TREEBUILDER_ROOT = 'norsys_seo';

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder() : TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root(self::BUNDLE_TREEBUILDER_ROOT);
        $rootNode
            ->children()
                ->arrayNode('rewrite')
                    ->info('Optional rewrite configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('remove_trailing_slash')
                            ->info('Enable remove trailing slash in urls')
                            ->defaultValue(false)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('translation')
                    ->info('Optional translation configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->info('Wether translation should be enabled or not for SEO meta tags')
                            ->defaultValue(false)
                        ->end()
                        ->scalarNode('domain')
                            ->info('Domain for translatable meta contents (if "null", use the app default domain)')
                            ->defaultValue(null)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('title')
                    ->info('Home for the title tag configuration')
                    ->children()
                        ->scalarNode('default')
                            ->info('Fallback value for title when no title defined for the requested route')
                            ->isRequired()
                        ->end()
                        ->arrayNode('pages')
                            ->info('Config for titles on a per-route basis')
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('metas')
                    ->info('Home for the meta tags configuration')
                    ->children()
                        ->arrayNode('defaults')
                            ->info('Fallback values for meta tags when no config found for the requested route')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->prototype('array')
                                ->children()
                                    // Here an exhaustive list of all possible attributes for a <meta> tag
                                    // Problem, name/charset/http-equiv/itemprop should be an EXCLUSIVE choice
                                    ->scalarNode('name')
                                    ->end()
                                    ->scalarNode('charset')
                                    ->end()
                                    ->scalarNode('http-equiv')
                                    ->end()
                                    ->scalarNode('itemprop')
                                    ->end()
                                    ->scalarNode('content')
                                    ->end()
                                    ->scalarNode('property')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('pages')
                            ->info('Config for meta informations on a per-route basis')
                            ->prototype('array')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')
                                        ->end()
                                        ->scalarNode('charset')
                                        ->end()
                                        ->scalarNode('http-equiv')
                                        ->end()
                                        ->scalarNode('itemprop')
                                        ->end()
                                        ->scalarNode('content')
                                        ->end()
                                        ->scalarNode('property')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()

                ->end()
                
                ->arrayNode('links')
                    ->info('Home for the link tags configuration')
                    ->children()
                        ->arrayNode('pages')
                            ->prototype('array')
                                ->prototype('array')
                                    ->children()
                                        // Here an exhaustive list of all possible attributes for a <link> tag
                                        ->scalarNode('rel')
                                        ->end()
                                        ->scalarNode('href')
                                        ->end()
                                        ->scalarNode('hreflang')
                                        ->end()
                                        ->scalarNode('media')
                                        ->end()
                                        ->scalarNode('title')
                                        ->end()
                                        ->scalarNode('type')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
               
            ->end();

        return $treeBuilder;
    }
}
