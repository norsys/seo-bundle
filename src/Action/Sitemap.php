<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Action;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class Sitemap
 * Responsible for displaying the sitemap XML file
 * To be exposed in the sitemap, a route must have the option
 * "sitemap" set to true in the routing config.
 *
 * Example:
 *
 * #app/config/routing.yml
 * home:
 *     path: /
 *     defaults: { _controller: AppBundle\Action\Home }
 *     options:
 *         sitemap: true
 */
class Sitemap
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * Sitemap constructor.
     *
     * @param RouterInterface $router
     * @param EngineInterface $templateEngine
     */
    public function __construct(RouterInterface $router, EngineInterface $templateEngine)
    {
        $this->router = $router;
        $this->templateEngine = $templateEngine;
    }

    /**
     * @return Response
     */
    public function __invoke() : Response
    {
        return new Response(
            $this->templateEngine->render(
                'NorsysSeoBundle::sitemap.xml.twig',
                [
                    'routes' => $this->getExposedRoutes()
                ]
            ),
            Response::HTTP_OK,
            [
                'Content-Type' => 'text/xml',
            ]
        );
    }

    /**
     * Return a list of exposed route names
     *
     * @return array
     */
    private function getExposedRoutes() : array
    {
        $routes = [];
        $routeCollection = $this->router->getRouteCollection();

        foreach ($routeCollection as $name => $route) {
            if ($route->hasOption('sitemap') === true
                && $route->getOption('sitemap') === true
            ) {
                $routes[] = $name;
            }
        }

        return $routes;
    }
}
