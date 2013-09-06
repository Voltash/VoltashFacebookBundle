<?php
namespace Voltash\FbApplicationBundle\Entity;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager implements UserManagerInterface
{
    private $em;
    private $repository;
    private $userClass;

    public function __construct(EntityManager $em, $userClass)
    {
        $this->em = $em;
        $this->userClass = $userClass;
        $this->repository = $this->em->getRepository($userClass);
    }
    /**
     * Creates an empty user instance.
     *
     * @return UserInterface
     */
    public function createUser()
    {
        return new $this->userClass;
    }

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteUser(UserInterface $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * Find a user by its Social Id.
     *
     * @param string $username
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserBySid($sid)
    {
        $q = $this->repository
            ->createQueryBuilder('u')
            ->where('u.sid = :sid')
            ->setParameter('sid', $sid)
            ->getQuery()
            ->getOneOrNullResult();

        return $q;
    }

    /**
     * Returns a collection with all user instances.
     *
     * @return \Traversable
     */
    public function findUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->userClass;
    }

    /**
     * Reloads a user.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->findUserBySid($user->getSid());
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updateUser(UserInterface $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }


}