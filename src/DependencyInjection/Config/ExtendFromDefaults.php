<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\DependencyInjection\Config;

/**
 * Class ExtendFromDefaults
 */
class ExtendFromDefaults
{
    /**
     * @var array
     */
    private $defaults;

    /**
     * @param array $seoConfig
     * @return array
     */
    public function __invoke(array $seoConfig) : array
    {
        $this->defaults = $seoConfig['metas']['defaults'];
        unset($seoConfig['metas']['defaults']);

        $extendedMetaData = [];
        foreach ($seoConfig['metas']['pages'] as $routeName => $metaTags) {
            $extendedMetaData[$routeName] = $this->getExtendedData($metaTags);
        }

        $seoConfig['metas'] = [
            'defaults' => $this->defaults,
            'pages'    => $extendedMetaData,
        ];
        
        return $seoConfig;
    }

    /**
     * Allow to identify an attribute universally based on its type:
     * name, http-equiv, charset, property or itemprop
     * This is useful for overriding defaults meta values when processing a route config
     *
     * @param array $attributes
     */
    private function buildUniqueMetaKey(array $attributes) : string
    {
        $tagTypes = [ 'name', 'http-equiv', 'charset', 'itemprop', 'property' ];
        foreach ($tagTypes as $key) {
            if (true === isset($attributes[$key])) {
                // The "charset" meta has to be handled differently
                if ($key === 'charset') {
                    return 'charset';
                }
                return sprintf('%s:%s', $key, $attributes[$key]);
            }
        }

        return '';
    }

    /**
     * @param array $routeData
     * @return array
     */
    private function getExtendedData(array $routeData) : array
    {
        $data = [];
        // Fill basis meta data with defauls
        foreach ($this->defaults as $attributes) {
            // Determine which key to use for default value storing/overriding
            $uniqueKey = $this->buildUniqueMetaKey($attributes);
            $data[$uniqueKey] = $attributes;
        }
        // Override with route values
        foreach ($routeData as $attributes) {
            // Determine which key to use for default value storing/overriding
            $uniqueKey = $this->buildUniqueMetaKey($attributes);
            $data[$uniqueKey] = $attributes;
        }
        return $data;
    }
}
