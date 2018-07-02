<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\Tests\Units\Helpers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

trait RequestHelper
{
    protected function createMockGetResponseEvent(Request $request, bool $isMasterRequest = false)
    {
        $event = $this->newMockInstance(GetResponseEvent::class);

        $this->calling($event)->getRequest      = $request;
        $this->calling($event)->isMasterRequest = $isMasterRequest;

        return $event;
    }

    protected function createMockRequest(
        string $method = null,
        string $scheme = null,
        string $host = null,
        string $path = null
    ) {
        $request = $this->newMockInstance(Request::class);

        $this->calling($request)->getMethod            = $method;
        $this->calling($request)->getScheme            = $scheme;
        $this->calling($request)->getHost              = $host;
        $this->calling($request)->getPathInfo          = $path;
        $this->calling($request)->getSchemeAndHttpHost = $scheme.'://'.$host;

        return $request;
    }
}
