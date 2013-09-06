<?php
namespace Voltash\FbApplicationBundle\Auth\Provider;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Voltash\FbApplicationBundle\Auth\Token\FbToken;
use Voltash\FbApplicationBundle\Util\Facebook\Facebook;

class FbProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $fbSdk;

    public function __construct(UserProviderInterface $userProvider, Facebook $sdk)
    {
        $this->userProvider = $userProvider;
        $this->fbSdk = $sdk;
    }

    public function authenticate(TokenInterface $token)
    {
        $this->fbSdk->setAccessToken($token->token);
        $userInfo = $this->fbSdk->api('me');

        if(isset($userInfo['id']))
        {
            $userInfo['token'] = $this->fbSdk->setExtendedAccessToken();
            $user = $this->userProvider->loadUserByUsername($userInfo);
            if ($user) {
                $this->userProvider->updateAccessToken($userInfo['token'], $user);
                $authenticatedToken = new FbToken($user->getRoles());
                $authenticatedToken->token = $token->token;
                $authenticatedToken->setUser($user);
                return $authenticatedToken;
            }

        }

        throw new AuthenticationException('The FbAuth authentication failed.');
     }

    public function supports(TokenInterface $token)
    {
        return $token instanceof FbToken;
    }
}