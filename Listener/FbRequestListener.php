<?php

namespace Voltash\FbApplicationBundle\Listener;

use Doctrine\Tests\Common\Annotations\Fixtures\Controller;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Voltash\FbApplicationBundle\Util\UrlHelper;

class FbRequestListener
{
    private $router;
    private $fanConfig;
    private $pageConfig;
    private $appConfig;
    private $urlHelper;
    private $securityContext;

    function __construct(Router $router, $config, $appConfig, $pageConfig, UrlHelper $urlHelper, SecurityContext $securityContext)
    {
        $this->fanConfig = array(
            'fanOnly' => false
        );
        $this->fanConfig = array_merge($this->fanConfig, $config);
        $this->pageConfig = $pageConfig;
        $this->appConfig = $appConfig;
        $this->router = $router;
        $this->urlHelper = $urlHelper;
        $this->securityContext = $securityContext;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        $request = $event->getRequest();

        if (count($_COOKIE) == 0 && strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
            $event->setResponse(new Response('<script>top.location.href= "'.$this->router->generate('fb_app_fix_safari', array(), true).'";</script>',200, array('content-type' => 'text/html')));
            return true;
        }
        if ($request->getPathInfo() == '/fix-safari/') {
            return true;
        }

        // Disable filter for this path
        $unFilter = array('/login_check');
        $session = $request->getSession();

        // check if  disable listener
        if ($session->has('disable')) {
            $session->remove('disable');
            return true;
        }

        // check if  disable listener
        if ($session->has('like')) {
            $session->remove('like');
            $event = $this->prepareForwardResponse($this->fanConfig['nonFanRoute'], array(), $event);
            return true;
        }

        if (($signedRequest = $this->urlHelper->parsePageSignedRequest())) {
            $signedRequest->app_data = isset($signedRequest->app_data) ? $signedRequest->app_data : null;
            $securityToken = $this->securityContext->getToken();

            if (isset($signedRequest->error)) {

            }

            //Check from config if allow use canvas or only fan page app
            if (!$this->pageConfig['canvas'] && !isset($signedRequest->page)) {
                $redirectUrl = $this->pageConfig['fan_page_url'].'app_'.$this->appConfig['appId'];
                $request->getSession()->set('redirect', $redirectUrl);
            }

            if (!in_array($request->getPathInfo(), $unFilter)) {
                //Check if user not Authenticated or Log in to app with new fb account, redirect to login check
                if ((isset($signedRequest->user_id) && is_null($securityToken))) {
                    if ($this->fanConfig['fanOnly'] && isset($signedRequest->page) && !$signedRequest->page->liked) {
                        $session->set('like', true);
                    }
                    $event = $this->prepareRedirectResponse(
                        'fb_app_login_check',
                        array('signed_request' => $_REQUEST['signed_request']),
                        $event
                    );

                    return true;
                }
            }


            if (!isset($signedRequest->user_id) || ($signedRequest->user_id != $securityToken->getUser()->getSid())) {
                $event = $this->prepareRedirectResponse('fb_app_logout', array(), $event);
                return true;
            }

            //Check if user liked fan page
            if ($this->fanConfig['fanOnly'] && isset($signedRequest->page) && !$signedRequest->page->liked) {
                $event = $this->prepareForwardResponse($this->fanConfig['nonFanRoute'], array(), $event);
                return true;
            }


        }
        return null;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        //fix session for ie and (safari =< 5.0)
        $event->getResponse()->headers->set('P3P', 'CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

    }

    private function prepareRedirectResponse($route, array $param, GetResponseEvent $event)
    {
        $url = $this->router->generate($route, $param);
        $event->setResponse(new RedirectResponse($url));

        return $event;

    }

    private function prepareForwardResponse($route, array $param, GetResponseEvent $event)
    {
        $routeController = $this->router->getRouteCollection()->get($route)->getDefaults();
        $event->getRequest()->getSession()->set('disable', true);
        $response = $event->getKernel()->forward($routeController['_controller'], $param);
        $event->setResponse($response);

        return $event;
    }


}