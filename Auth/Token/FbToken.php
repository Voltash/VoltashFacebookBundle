<?php
namespace Voltash\FbApplicationBundle\Auth\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class FbToken extends AbstractToken
{
    public $token;
    public $tokenExpiration;

    public function __construct(array $roles = array())
    {

        parent::__construct($roles);
        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }

    public function serialize()
    {
        $parentSerialize = parent::serialize();
        return serialize(array($this->token, $parentSerialize));
    }

    public function unserialize($serialized)
    {
        list($this->token, $parentSerialize) = unserialize($serialized);
        parent::unserialize($parentSerialize);
    }


}
