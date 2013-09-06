<?php

namespace Voltash\FbApplicationBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Voltash\FbApplicationBundle\Entity\UserManagerInterface;


class UserProvider implements UserProviderInterface
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->findUser($username['id']);

        if (!$user) {
            $user = $this->createUser($username);
        }

        return $user;
    }

    public function updateAccessToken($token, UserInterface $user)
    {
        if ($token) {
            $user->setLongToken($token);
            $this->userManager->updateUser($user);
        }
        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        $user = $this->userManager->refreshUser($user);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }

    /**
     * Finds a user by username.
     *
     * This method is meant to be an extension point for child classes.
     *
     * @param string $username
     *
     * @return UserInterface|null
     */
    protected function findUser($sid)
    {
        return $this->userManager->findUserBySid($sid);
    }

    /**
     * Create new user
     *
     * @param array $data
     *
     * @return UserInterface
     */
    protected function createUser($data)
    {
        if (isset($data['id'])) {
            $user = $this->userManager->createUser();
            $user->setSid($data['id']);
            $user->setName($data['first_name']);
            $user->setSurname($data['last_name']);
            $this->userManager->updateUser($user);
        } else {
            throw new \Exception('User sid is null');
        }

        return $user;
    }


}
