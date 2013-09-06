<?php
namespace Voltash\FbApplicationBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Created by JetBrains PhpStorm.
 * User: volt
 * Date: 02.09.13
 * Time: 14:23
 * To change this template use File | Settings | File Templates.
 */
interface UserManagerInterface
{
    /**
     * Creates an empty user instance.
     *
     * @return UserInterface
     */
    public function createUser();

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteUser(UserInterface $user);


    /**
     * Find a user by its Social Id.
     *
     * @param string $username
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserBySid($username);


    /**
     * Returns a collection with all user instances.
     *
     * @return \Traversable
     */
    public function findUsers();

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass();

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updateUser(UserInterface $user);

    public function refreshUser(UserInterface $user);

}
