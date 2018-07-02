<?php
declare(strict_types = 1);

namespace Norsys\SeoBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class UrlRewriteListener
 */
class UrlRewriteListener
{
    /**
     * @var boolean
     */
    private $removeTrailingSlashEnabled;

    /**
     * UrlRewriteListener constructor.
     *
     * @param boolean $removeTrailingSlashEnabled
     */
    public function __construct(bool $removeTrailingSlashEnabled)
    {
        $this->removeTrailingSlashEnabled = $removeTrailingSlashEnabled;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequestRemoveTrailingSlash(GetResponseEvent $event)
    {
        if ($this->removeTrailingSlashEnabled === false
            || $event->isMasterRequest() === false
        ) {
            return;
        }

        $request = $event->getRequest();

        if ($request->getMethod() !== Request::METHOD_GET
            || $request->getPathInfo() === '/'
            || substr($request->getPathInfo(), -1) !== '/'
        ) {
            return;
        }

        $redirectUrl = sprintf(
            '%s%s',
            $request->getSchemeAndHttpHost(),
            rtrim($request->getPathInfo(), '/')
        );

        $event->setResponse(new RedirectResponse(
            $redirectUrl,
            301
        ));
    }
}
