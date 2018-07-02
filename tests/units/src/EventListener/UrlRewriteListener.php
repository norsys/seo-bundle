<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Units\EventListener;

use Norsys\SeoBundle\Tests\Units\Helpers\RequestHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Norsys\SeoBundle\Tests\Units\Test;

class UrlRewriteListener extends Test
{
    use RequestHelper;

    public function testOnKernelRequestRemoveTrailingSlashOnDisabled()
    {
        $this
            ->assert('Url rewrite with remove trailing slash disabled')
            ->given(
                $requestMock = $this->createMockRequest(),
                $masterRequest = true,
                $getResponseEventMock = $this->createMockGetResponseEvent($requestMock, $masterRequest),
                $removeTrailingSlashEnabled = false,
                $this->newTestedInstance($removeTrailingSlashEnabled)
            )
            ->if($this->testedInstance->onKernelRequestRemoveTrailingSlash($getResponseEventMock))
            ->then
                ->mock($getResponseEventMock)
                    ->call('isMasterRequest')
                        ->never()
                ->mock($getResponseEventMock)
                    ->call('setResponse')
                        ->never();
    }

    public function testOnKernelRequestRemoveTrailingSlashWithSubRequest()
    {
        $this
            ->assert('Url rewrite with remove trailing slash with sub request')
            ->given(
                $requestMock = $this->createMockRequest(),
                $masterRequest = false,
                $getResponseEventMock = $this->createMockGetResponseEvent($requestMock, $masterRequest),
                $removeTrailingSlashEnabled = true,
                $this->newTestedInstance($removeTrailingSlashEnabled)
            )
            ->if($this->testedInstance->onKernelRequestRemoveTrailingSlash($getResponseEventMock))
            ->then
                ->mock($getResponseEventMock)
                    ->call('isMasterRequest')
                        ->once()
                ->mock($getResponseEventMock)
                    ->call('setResponse')
                        ->never();
    }

    public function testOnKernelRequestRemoveTrailingSlashWithoutMethodGet()
    {
        $this
            ->assert('Url rewrite with remove trailing slash without method get')
            ->given(
                $method = Request::METHOD_POST,
                $scheme = 'http',
                $host = 'sample.com',
                $path = '/hello-world',
                $requestMock = $this->createMockRequest($method, $scheme, $host, $path),
                $masterRequest = true,
                $getResponseEventMock = $this->createMockGetResponseEvent($requestMock, $masterRequest),
                $removeTrailingSlashEnabled = true,
                $this->newTestedInstance($removeTrailingSlashEnabled)
            )
            ->if($this->testedInstance->onKernelRequestRemoveTrailingSlash($getResponseEventMock))
            ->then
                ->mock($requestMock)
                    ->call('getMethod')
                        ->once()
                ->mock($getResponseEventMock)
                    ->call('setResponse')
                        ->never();
    }

    public function testOnKernelRequestRemoveTrailingSlashWithHomePage()
    {
        $this
            ->assert('Url rewrite with remove trailing slash with home page')
            ->given(
                $method = Request::METHOD_GET,
                $scheme = 'http',
                $host = 'sample.com',
                $path = '/',
                $requestMock = $this->createMockRequest($method, $scheme, $host, $path),
                $masterRequest = true,
                $getResponseEventMock = $this->createMockGetResponseEvent($requestMock, $masterRequest),
                $removeTrailingSlashEnabled = true,
                $this->newTestedInstance($removeTrailingSlashEnabled)
            )
            ->if($this->testedInstance->onKernelRequestRemoveTrailingSlash($getResponseEventMock))
            ->then
                ->mock($requestMock)
                    ->call('getPathInfo')
                        ->once()
                ->mock($getResponseEventMock)
                    ->call('setResponse')
                        ->never();
    }

    public function testOnKernelRequestRemoveTrailingSlashWithoutTrailingSlash()
    {
        $this
            ->assert('Url rewrite with remove trailing slash without trailing slash')
            ->given(
                $method = Request::METHOD_GET,
                $scheme = 'http',
                $host = 'sample.com',
                $path = '/hello-world',
                $requestMock = $this->createMockRequest($method, $scheme, $host, $path),
                $masterRequest = true,
                $getResponseEventMock = $this->createMockGetResponseEvent($requestMock, $masterRequest),
                $removeTrailingSlashEnabled = true,
                $this->newTestedInstance($removeTrailingSlashEnabled)
            )
            ->if($this->testedInstance->onKernelRequestRemoveTrailingSlash($getResponseEventMock))
            ->then
                ->mock($requestMock)
                    ->call('getPathInfo')
                        ->twice()
                ->mock($getResponseEventMock)
                    ->call('setResponse')
                        ->never();
    }

    public function testOnKernelRequestRemoveTrailingSlashWithTrailingSlash()
    {
        $this
            ->assert('Url rewrite with remove trailing slash with trailing slash')
            ->given(
                $method = Request::METHOD_GET,
                $scheme = 'http',
                $host = 'sample.com',
                $path = '/hello-world/',
                $requestMock = $this->createMockRequest($method, $scheme, $host, $path),
                $masterRequest = true,
                $getResponseEventMock = $this->createMockGetResponseEvent($requestMock, $masterRequest),
                $removeTrailingSlashEnabled = true,
                $this->newTestedInstance($removeTrailingSlashEnabled),
                $statusCode = 301,
                $url = $scheme.'://'.$host.rtrim($path, '/'),
                $this->calling($getResponseEventMock)->setResponse = function ($response) use ($statusCode, $url) {
                    $this
                        ->object($response)
                            ->isInstanceOf(RedirectResponse::class)
                        ->if($statusCodeResponse = $response->getStatusCode())
                        ->then
                            ->integer($statusCodeResponse)
                                ->isEqualTo($statusCode)
                        ->if($targetUrlResponse = $response->getTargetUrl())
                        ->then
                            ->string($targetUrlResponse)
                                ->isEqualTo($url);
                }

            )
            ->if($this->testedInstance->onKernelRequestRemoveTrailingSlash($getResponseEventMock))
            ->then
                ->mock($getResponseEventMock)
                    ->call('setResponse')
                        ->once();
    }
}
