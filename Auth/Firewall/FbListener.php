<?php
namespace Voltash\FbApplicationBundle\Auth\Firewall;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Voltash\FbApplicationBundle\Auth\Token\FbToken;
use Voltash\FbApplicationBundle\Util\UrlHelper;

class FbListener extends AbstractAuthenticationListener
{
    private $urlHelper;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, SessionAuthenticationStrategyInterface $sessionStrategy, HttpUtils $httpUtils, $providerKey, AuthenticationSuccessHandlerInterface $successHandler, AuthenticationFailureHandlerInterface $failureHandler, array $options = array(), LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null, $app, UrlHelper $urlHelper, $page)
    {
        parent::__construct($securityContext, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey, $successHandler, $failureHandler, $options, $logger, $dispatcher);
        $this->app = $app;
        $this->page = $page;
        $this->urlHelper = $urlHelper;
    }

    public function attemptAuthentication(Request $request)
    {
        try
        {
            if ($signedRequest = $this->urlHelper->parsePageSignedRequest())
            {
                if (!isset($signedRequest->oauth_token))
                    throw new \Exception('Facebook verification failed');
                else
                {
                    $token = new FbToken();
                    $token->token = $signedRequest->oauth_token;
                    return $this->authenticationManager->authenticate($token);
                }
            }
        }
        catch (Exception $e)
        {
            echo('<script>top.location.href= "'.$this->page['fan_page_url'].'";</script>');
            exit;
        }
    }
}