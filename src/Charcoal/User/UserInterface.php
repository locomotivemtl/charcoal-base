<?php

namespace Charcoal\User;

/**
 *
 */
interface UserInterface
{
    /**
     * @return string
     */
    public static function sessionKey();

    /**
     * @param array $data The data map to set.
     * @return UserInterface Chainable
     */
    public function setData(array $data);

    /**
     * Force a lowercase username
     *
     * @param string $username The username (also the login name).
     * @return UserInterface Chainable
     */
    public function setUsername($username);

    /**
     * @return string
     */
    public function username();

    /**
     * @param string $email The user email.
     * @return UserInterface Chainable
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function email();

    /**
     * @param string|null $password The user password. Encrypted in storage.
     * @return UserInterface Chainable
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function password();

    /**
     * @param array $groups The UserGroups this user belongs to.
     * @return UserInterface Chainable
     */
    public function setGroups(array $groups);

    /**
     * @param array|UserGroupInterface $group The group to add.
     * @return UserInterface Chainable
     */
    public function addGroup($group);

    /**
     * @return array The UserGroup list attached to this user
     */
    public function groups();

    /**
     * @param array $permissions The user permissions to set. Either as array or UserPermission objects.
     * @return UserInterface Chainable
     */
    public function setPermissions(array $permissions);

    /**
     * @param string                        $permissionIdent The permission identifier.
     * @param array|UserPermissionInterface $permission      The user permission object/array to add.
     * @return UserInterface Chainable
     */
    public function addPermission($permissionIdent, $permission);

    /**
     * @return UserPermissionInterface[] The UserPermission list (array) attached to this user.
     */
    public function permissions();

    /**
     * @param boolean $active The active flag.
     * @return UserInterface Chainable
     */
    public function setActive($active);

    /**
     * @return boolean
     */
    public function active();

    /**
     * @param string|DateTime $ts The last login date.
     * @return UserInterface Chainable
     */
    public function setLastLoginDate($ts);

    /**
     * @return DateTime
     */
    public function lastLoginDate();

    /**
     * @param string|integer|null $ip The last login IP address.
     * @return UserInterface Chainable
     */
    public function setLastLoginIp($ip);

    /**
     * Get the last login IP in x.x.x.x format
     * @return string
     */
    public function lastLoginIp();

    /**
     * @param string|DateTime $ts The last password date.
     * @return UserInterface Chainable
     */
    public function setLastPasswordDate($ts);

    /**
     * @return DateTime
     */
    public function lastPasswordDate();

    /**
     * @param integer|string|null $ip The last password IP.
     * @return UserInterface Chainable
     */
    public function setLastPasswordIp($ip);

    /**
     * Get the last password change IP in x.x.x.x format.
     *
     * @return string
     */
    public function lastPasswordIp();

    /**
     * @param string $token The login token.
     * @return UserInterface Chainable
     */
    public function setLoginToken($token);

    /**
     * @return string
     */
    public function loginToken();

    /**
     * @param string $username Username.
     * @param string $password Password.
     * @return boolean Login success / failure.
     */
    public function authenticate($username, $password);

    /**
     * Reset the password.
     *
     * Encrypt the password and re-save the object in the database.
     * Also updates the last password date & ip.
     *
     * @param string $plainPassword The plain (non-encrypted) password to reset to.
     * @return UserInterface Chainable
     */
    public function resetPassword($plainPassword);

    /**
     * Get the currently authenticated user.
     *
     * @param boolean $reinit Whether to reload user data from source.
     * @return UserInterface|null
     */
    public static function getAuthenticated($reinit = true);
}
