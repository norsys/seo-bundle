<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Units\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Route;
use Norsys\SeoBundle\Tests\Units\Test;
use mock\Symfony\Component\Routing\RouterInterface as MockOfRouter;
use mock\Symfony\Component\Templating\EngineInterface as MockOfTemplateEngine;
use mock\Symfony\Component\Routing\RouteCollection as MockOfRouteCollection;

class Sitemap extends Test
{
    public function testOnNoExposedRoutes()
    {
        $this
            ->assert('Test if config has not exposed route.')
            ->given(
                $router = new MockOfRouter,
                $routeCollection = new MockOfRouteCollection,
                $this->calling($router)->getRouteCollection = $routeCollection,
                $this->calling($routeCollection)->getIterator = new \ArrayIterator([]),
                $templateEngine = new MockOfTemplateEngine,
                $this->newTestedInstance($router, $templateEngine),
                $this->calling($templateEngine)->render = 'template'
            )
            ->if($resultResponse = $this->testedInstance->__invoke())
            ->then
                ->object($resultResponse)
                    ->isInstanceOf(Response::class)
            ->if($responseStatus = $resultResponse->getStatusCode())
            ->then
                ->integer($responseStatus)
                    ->isEqualTo(Response::HTTP_OK)
            ->if($headers = $resultResponse->headers)
            ->then
                ->object($headers)
                    ->isInstanceOf(ResponseHeaderBag::class)
            ->if($result = $headers->all())
            ->then
                ->string($result['content-type'][0])
                    ->isEqualTo('text/xml')
                ->mock($templateEngine)
                    ->receive('render')
                        ->withArguments(
                            'NorsysSeoBundle::sitemap.xml.twig',
                            ['routes' => []]
                        )->once;
    }

    public function testOnAvailableExposedRoutes()
    {
        $this
            ->assert('Test if config has exposed routes.')
            ->given(
                $router = new MockOfRouter,
                $routeCollection = new MockOfRouteCollection,
                $this->calling($router)->getRouteCollection = $routeCollection,
                $this->calling($routeCollection)->getIterator = function () {
                    return new \ArrayIterator(
                        [
                            'route_name_1' => new Route('path_1', [], [], []),
                            'route_name_2' => new Route('path_2', [], [], ['sitemap' => true])
                        ]
                    );
                },
                $templateEngine = new MockOfTemplateEngine,
                $this->newTestedInstance($router, $templateEngine),
                $template = uniqid(),
                $this->calling($templateEngine)->render = $template
            )
            ->if($resultResponse = $this->testedInstance->__invoke())
            ->then
                ->object($resultResponse)
                    ->isInstanceOf(Response::class)
            ->if($responseStatus = $resultResponse->getStatusCode())
            ->then
                ->integer($responseStatus)
                    ->isEqualTo(Response::HTTP_OK)
            ->if($headers = $resultResponse->headers)
            ->then
                ->object($headers)
                    ->isInstanceOf(ResponseHeaderBag::class)
            ->if($result = $headers->all())
            ->then
                ->string($result['content-type'][0])
                    ->isEqualTo('text/xml')
            ->mock($templateEngine)
                ->receive('render')
                    ->withArguments(
                        'NorsysSeoBundle::sitemap.xml.twig',
                        ['routes' => ['route_name_2']]
                    )->once;
    }
}
