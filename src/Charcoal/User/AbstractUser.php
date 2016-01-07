<?php

namespace Charcoal\User;

// Dependencies from `PHP`
use \DateTime;
use \DateTimeInterface;
use \InvalidArgumentException;

// Module `charcoal-core` dependencies
use \Charcoal\Config\ConfigurableInterface;
use \Charcoal\Config\ConfigurableTrait;

// Module `charcoal-base` dependencies
use \Charcoal\Object\Content;

// Local namespace (charcoal-base) dependencies
use \Charcoal\User\UserConfig;
use \Charcoal\User\UserInterface;

/**
 * Full implementation, as abstract class, of the `UserInterface`.
 */
abstract class AbstractUser extends Content implements
    UserInterface,
    ConfigurableInterface
{
    use ConfigurableTrait;

    /**
     * The username should be unique and mandatory.
     * @var string $username
     */
    private $username = '';

    /**
     * The password is stored encrypted in the database.
     * @var string $password
     */
    private $password;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var array $groups
     */
    private $groups;

    /**
     * @var array $permissions
     */
    private $permissions;

    /**
     * @var boolean $active
     */
    private $active = true;

    /**
     * The date of the latest (successful) login
     * @var DateTime|null $last_login_date
     */
    private $last_login_date;

    /**
     * @var string $last_login_ip
     */
    private $last_login_ip;

    /**
     * The date of the latest password change
     * @var DateTime|null
     */
    private $last_password_date;

    /**
     * @var string $last_password_ip
     */
    private $last_password_ip;

    /**
     * If the login token is set (not empty), then the user should be prompted to
     * reset his password after login / enter the token to continue
     * @var string $login_token
     */
    private $login_token = '';

    /**
     * IndexableTrait > key()
     *
     * @return string
     */
    public function key()
    {
        return 'username';
    }

    /**
     * Force a lowercase username
     *
     * @param string $username The username (also the login name).
     * @throws InvalidArgumentException If the username is not a string.
     * @return User Chainable
     */
    public function set_username($username)
    {
        if (!is_string($username)) {
            throw new InvalidArgumentException(
                'Set user username: Username must be a string'
            );
        }
        $this->username = mb_strtolower($username);
        return $this;
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @param string $email The user email.
     * @throws InvalidArgumentException If the email is not a string.
     * @return User Chainable
     */
    public function set_email($email)
    {
        if (!is_string($email)) {
            throw new InvalidArgumentException(
                'Set user email: Email must be a string'
            );
        }
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @param string|null $password The user password. Encrypted in storage.
     * @throws InvalidArgumentException If the password is not a string (or null, to reset).
     * @return UserInterface Chainable
     */
    public function set_password($password)
    {
        if ($password == null) {
            $this->password = $password;
        } elseif (is_string($password)) {
            $this->password = $password;
        } else {
            throw new InvalidArgumentException(
                'Set user password: Password must be a string'
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * @param array $groups The UserGroups this user belongs to.
     * @return UserInterface Chainable
     */
    public function set_groups(array $groups)
    {
        $this->groups = [];
        foreach ($groups as $g) {
            $this->add_group($g);
        }
        return $this;
    }
    /**
     * @param array|UserGroup $group The group to ad.
     * @throws InvalidArgumentException If the group is not an object or array.
     * @return UserInterface Chainable
     */
    public function add_group($group)
    {
        if (is_array($group)) {
            $g = $this->create_group();
            $g->set_data($group);
            $group = $g;
        }
        if (!($group instanceof UserGroupInterface)) {
            throw new InvalidArgumentException(
                'Invalid user group.'
            );
        }
        $this->groups[] = $group;
        return $this;
    }

    /**
     * @return UserGroupInterface
     */
    abstract public function create_group();

    /**
     * @return array The UserGroup list attached to this user
     */
    public function groups()
    {
        return $this->groups;
    }

    /**
     * @param array $permissions The user permissions (map) to set.
     * @return UserInterface Chainable
     */
    public function set_permissions(array $permissions)
    {
        $this->permissions = [];
        foreach ($permissions as $permission_ident => $permission) {
            $this->add_permission($permission_ident, $permission);
        }
        return $this;
    }

    /**
     * @param string                        $permission_ident The permission identifier.
     * @param array|UserPermissionInterface $permission       The user permission object or array.
     * @throws InvalidArgumentException If the ident is not a string or if the permission is not an object or an array.
     * @return UserInterface Chainable
     */
    public function add_permission($permission_ident, $permission)
    {
        if (!is_string($permission_ident)) {
            throw new InvalidArgumentException(
                'Permission identifier must be a string.'
            );
        }
        if (is_array($permission)) {
            $p = $this->create_permission();
            $p->set_data($permission);
            $permission = $p;
        } elseif (!($permission instanceof UserPermissionInterface)) {
            throw new InvalidArgumentException(
                'Invalid permissions'
            );
        }
        $this->permissions[$permission_ident] = $permission;
        return $this;
    }

    /**
     * @return UserPermissionInterface[] The UserPersmission list attached to this user
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * @param array|null $data Optional. The permission data.
     * @return UserPermissionInterface
     */
    abstract public function create_permission($data = null);


    /**
     * @param boolean $active The active flag.
     * @return UserInterface Chainable
     */
    public function set_active($active)
    {
        $this->active = !!$active;
        return $this;
    }
    /**
     * @return boolean
     */
    public function active()
    {
        return $this->active;
    }

    /**
     * @param string|DateTime|null $last_login_date The last login date.
     * @throws InvalidArgumentException If the ts is not a valid datetime.
     * @return AbstractUser Chainable
     */
    public function set_last_login_date($last_login_date)
    {
        if ($last_login_date === null) {
            $this->last_login_date = null;
            return $this;
        }
        if (is_string($last_login_date)) {
            try {
                $last_login_date = new DateTime($last_login_date);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    sprintf('Invalid login date (%s)', $e->getMessage())
                );
            }
        }
        if (!($last_login_date instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Last Login Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->last_login_date = $last_login_date;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function last_login_date()
    {
        return $this->last_login_date;
    }

    /**
     * @param string|integer|null $ip The last login IP address.
     * @throws InvalidArgumentException If the IP is not an IP string, an integer, or null.
     * @return UserInterface Chainable
     */
    public function set_last_login_ip($ip)
    {
        if ($ip === null) {
            $this->last_login_ip = null;
            return $this;
        }
        if (is_int($ip)) {
            $ip = long2ip($ip);
        }
        if (!is_string($ip)) {
            throw new InvalidArgumentException(
                'Invalid IP address'
            );
        }
        $this->last_login_ip = $ip;
        return $this;
    }
    /**
     * Get the last login IP in x.x.x.x format
     * @return string
     */
    public function last_login_ip()
    {
        return $this->last_login_ip;
    }

    /**
     * @param string|DateTime|null $last_password_date The last password date.
     * @throws InvalidArgumentException If the passsword date is not a valid DateTime.
     * @return UserInterface Chainable
     */
    public function set_last_password_date($last_password_date)
    {
        if ($last_password_date === null) {
            $this->last_password_date = null;
            return $this;
        }
        if (is_string($last_password_date)) {
            try {
                $last_password_date = new DateTime($last_password_date);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    sprintf('Invalid last password date (%s)', $e->getMessage())
                );
            }
        }
        if (!($last_password_date instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Last Password Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->last_password_date = $last_password_date;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function last_password_date()
    {
        return $this->last_password_date;
    }

    /**
     * @param integer|string|null $ip The last password IP.
     * @throws InvalidArgumentException If the IP is not null, an integer or an IP string.
     * @return UserInterface Chainable
     */
    public function set_last_password_ip($ip)
    {
        if ($ip === null) {
            $this->last_password_ip = null;
            return $this;
        }
        if (is_int($ip)) {
            $ip = long2ip($ip);
        }
        if (!is_string($ip)) {
            throw new InvalidArgumentException(
                'Invalid IP address'
            );
        }
        $this->last_password_ip = $ip;
        return $this;
    }
    /**
     * Get the last password change IP in x.x.x.x format
     *
     * @return string
     */
    public function last_password_ip()
    {
        return $this->last_password_ip;
    }

    /**
     * @param string $token The login token.
     * @throws InvalidArgumentException If the token is not a string.
     * @return UserInterface Chainable
     */
    public function set_login_token($token)
    {
        if (!is_string($token)) {
            throw new InvalidArgumentException(
                'Login Token must be a string'
            );
        }
        $this->login_token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function login_token()
    {
        return $this->login_token;
    }

    /**
     * Attempt to log in a user with a username + password.
     *
     * @param string $username Username.
     * @param string $password Password.
     * @throws InvalidArgumentException If username or password is not a string.
     * @return boolean Login success / failure.
     */
    public function authenticate($username, $password)
    {

        if (!is_string($username) || !is_string($password)) {
            throw new InvalidArgumentException(
                'Username and password must be strings'
            );
        }

        // Force lowercase
        $username = mb_strtolower($username);

        // Load the user by username
        $this->load($username);

        if ($this->username() != $username) {
            $this->login_failed($username);
            return false;
        }
        if ($this->active() === false) {
            $this->login_failed($username);
            return false;
        }

        // Validate password
        if (password_verify($password, $this->password())) {
            if (password_needs_rehash($this->password(), PASSWORD_DEFAULT)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                // @todo Update user with new hash
                $this->update(['password']);
            }

            $this->login();
            return true;
        } else {
            $this->login_failed($username);
            return false;
        }
    }

    /**
     * Log in the user (in session)
     *
     * Called when the authentication is successful.
     *
     * @return boolean Success / Failure
     */
    public function login()
    {
        if (!$this->id()) {
            return false;
        }

        $this->set_last_login_date('now');
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        if ($ip) {
            $this->set_last_login_ip($ip);
        }
        $this->update(['last_login_ip', 'last_login_date']);

        // Save to session
        $_SESSION[static::session_key()] = $this;

        return true;
    }

    /**
     * @return boolean
     */
    public function log_login()
    {
        // @todo
        return true;
    }

    /**
     * Failed authentication callback
     *
     * @param string $username The failed username.
     * @return void
     */
    public function login_failed($username)
    {
        $this->set_username('');
        $this->set_permissions([]);
        $this->set_groups([]);

        $this->log_login_failed($username);
    }

    /**
     * @param string $username The username to log failure.
     * @return boolean
     */
    public function log_login_failed($username)
    {
        // @todo
        return true;
    }

    /**
     * Empties the session var associated to the session key.
     *
     * @return boolean Logged out or not.
     */
    public function logout()
    {
        // Irrelevant call...
        if (!$this->id()) {
            return false;
        }

        $_SESSION[static::session_key()] = null;
        unset($_SESSION[static::session_key()]);

        return true;
    }

    /**
     * Reset the password.
     *
     * Encrypt the password and re-save the object in the database.
     * Also updates the last password date & ip.
     *
     * @param string $plain_password The plain (non-encrypted) password to reset to.
     * @throws InvalidArgumentException If the plain password is not a string.
     * @return UserInterface Chainable
     */
    public function reset_password($plain_password)
    {
        if (!is_string($plain_password)) {
            throw new InvalidArgumentException(
                'Can not change password: password is not a string.'
            );
        }

        $hash = password_hash($plain_password, PASSWORD_DEFAULT);
        $this->set_password($hash);

        $this->set_last_password_date('now');
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        if ($ip) {
            $this->set_last_password_ip($ip);
        }

        if ($this->id()) {
            $this->update(['password', 'last_password_date', 'last_password_ip']);
        }

        return $this;
    }

    /**
     * Get the currently authenticated user (from session)
     *
     * Return null if there is no current user in logged into
     *
     * @param boolean $reinit Optional. Whether to reload user data from source.
     * @throws Exception If the user from session is invalid.
     * @return UserInterface|null
     */
    public static function get_authenticated($reinit = true)
    {
        if (!isset($_SESSION[static::session_key()])) {
            return null;
        }
        $user_class = get_called_class();
        $user = $_SESSION[static::session_key()];
        if (!($user instanceof $user_class)) {
            unset($_SESSION[static::session_key()]);
            throw new Exception('Invalid user in session');
        }

        // Optionally re-init the object from source (database)
        if ($reinit) {
            $user_id = $user->id();

            $user = new $user_class([
                'logger'=>\Charcoal\Charcoal::logger()
            ]);
            $user->load($user_id);
            // Save back to session
            $_SESSION[static::session_key()] = $user;
        }

        // Inactive users can not authenticate
        if (!$user->active()) {
            // @todo log error
            return null;
        }

        // Make sure the user is valid.
        if (!$user->id() || !$user->username()) {
            return null;
        }

        return $user;
    }

    /**
     * ConfigurableInterface > create_config()
     *
     * @param array $data Optional. Configuration data.
     * @return UserConfig
     */
    public function create_config(array $data = null)
    {
        $config = new UserConfig();
        if (is_array($data)) {
            $config->set_data($data);
        }
        return $config;
    }
}
