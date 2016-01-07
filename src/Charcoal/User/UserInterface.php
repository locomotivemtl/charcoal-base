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
    public static function session_key();

    /**
     * @param array $data The data map to set.
     * @return UserInterface Chainable
     */
    public function set_data(array $data);

    /**
     * Force a lowercase username
     *
     * @param string $username The username (also the login name).
     * @return UserInterface Chainable
     */
    public function set_username($username);

    /**
     * @return string
     */
    public function username();

    /**
     * @param string $email The user email.
     * @return UserInterface Chainable
     */
    public function set_email($email);

    /**
     * @return string
     */
    public function email();

    /**
     * @param string|null $password The user password. Encrypted in storage.
     * @return UserInterface Chainable
     */
    public function set_password($password);

    /**
     * @return string
     */
    public function password();

    /**
     * @param array $groups The UserGroups this user belongs to.
     * @return UserInterface Chainable
     */
    public function set_groups(array $groups);

    /**
     * @param array|UserGroupInterface $group The group to add.
     * @return UserInterface Chainable
     */
    public function add_group($group);

    /**
     * @return array The UserGroup list attached to this user
     */
    public function groups();

    /**
     * @param array $permissions The user permissions to set. Either as array or UserPermission objects.
     * @return UserInterface Chainable
     */
    public function set_permissions(array $permissions);

    /**
     * @param string                        $permission_ident The permission identifier.
     * @param array|UserPermissionInterface $permission       The user permission object/array to add.
     * @return UserInterface Chainable
     */
    public function add_permission($permission_ident, $permission);

    /**
     * @return UserPermissionInterface[] The UserPermission list (array) attached to this user.
     */
    public function permissions();

    /**
     * @param boolean $active The active flag.
     * @return UserInterface Chainable
     */
    public function set_active($active);

    /**
     * @return boolean
     */
    public function active();

    /**
     * @param string|DateTime $ts The last login date.
     * @return UserInterface Chainable
     */
    public function set_last_login_date($ts);

    /**
     * @return DateTime
     */
    public function last_login_date();

    /**
     * @param string|integer|null $ip The last login IP address.
     * @return UserInterface Chainable
     */
    public function set_last_login_ip($ip);

    /**
     * Get the last login IP in x.x.x.x format
     * @return string
     */
    public function last_login_ip();

    /**
     * @param string|DateTime $ts The last password date.
     * @return UserInterface Chainable
     */
    public function set_last_password_date($ts);

    /**
     * @return DateTime
     */
    public function last_password_date();

    /**
     * @param integer|string|null $ip The last password IP.
     * @return UserInterface Chainable
     */
    public function set_last_password_ip($ip);

    /**
     * Get the last password change IP in x.x.x.x format.
     *
     * @return string
     */
    public function last_password_ip();

    /**
     * @param string $token The login token.
     * @return UserInterface Chainable
     */
    public function set_login_token($token);

    /**
     * @return string
     */
    public function login_token();

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
     * @param string $plain_password The plain (non-encrypted) password to reset to.
     * @return UserInterface Chainable
     */
    public function reset_password($plain_password);

    /**
     * Get the currently authenticated user.
     *
     * @param boolean $reinit Whether to reload user data from source.
     * @return UserInterface|null
     */
    public static function get_authenticated($reinit = true);
}
