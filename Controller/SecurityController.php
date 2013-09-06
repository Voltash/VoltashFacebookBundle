<?php
namespace Voltash\FbApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $fbAppConfig = $this->container->getParameter('fb_app.app');
        $fbPageConfig = $this->container->getParameter('fb_app.page');

        $url = $fbPageConfig['canvas_page_url'];
        if ($fbPageConfig['fan_page'])
            $url = $fbPageConfig['fan_page_url'].'app_'.$fbAppConfig['appId'];
        $params = array(
            'scope' => $this->container->getParameter('fb_app.scope'),
            'redirect_uri' => $url,
        );
        $loginUrl = $this->get('fb.sdk')->getLoginUrl($params);

        return new Response('<script>top.location.href= "'.$loginUrl.'";</script>',200, array('content-type' => 'text/html'));
    }

    public function safariFixAction()
    {
        $fbAppConfig = $this->container->getParameter('fb_app.app');
        $fbPageConfig = $this->container->getParameter('fb_app.page');
        $url = $fbPageConfig['fan_page_url'].'app_'.$fbAppConfig['appId'];
        $session = $this->getRequest()->getSession();
        $session->set('fix', 'fix safari');
        return new Response('<script>top.location.href= "'.$url.'";</script>',200, array('content-type' => 'text/html'));
    }

}