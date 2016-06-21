<?php

namespace Charcoal\User;

// Dependencies from 'PSR-3' (Logging)
use \Psr\Log\LoggerAwareInterface;
use \Psr\Log\LoggerAwareTrait;

// Module `charcoal-factory` dependencies
use \Charcoal\Factory\FactoryInterface;

/**
 * Authenticator helps with user authentication / login.
 *
 * ## Constructor dependencies
 *
 * Constructor dependencies are passed as an array of `key=>value` pair.
 * The required dependencies are:
 *
 * - `logger` A PSR3 logger instance
 * - `user_type` The user object type (FQN or ident)
 * - `user_factory` The Factory used to instanciate new users.
 * - `token_type` The auth token object type (FQN or ident)
 * - `token_factory` The Factory used to instanciate new auth tokens.
 */
class Authenticator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var string $userType
     */
    private $userType;

    /**
     * @var FactoryInterface $userFactory
     */
    private $userFactory;

    /**
     * @var string $tokenType
     */
    private $tokenType;

    /**
     * @var FactoryInterface $tokenFactory
     */
    private $tokenFactory;

    /**
     * @param array $data Class dependencies.
     */
    public function __construct(array $data)
    {
        $this->setLogger($data['logger']);
        $this->setUserType($data['user_type']);
        $this->setUserFactory($data['user_factory']);
        $this->setTokenType($data['token_type']);
        $this->setTokenFactory($data['token_factory']);
    }

    /**
     * @param string $type The user (obj) type.
     * @return AdminAuthenticator Chainable
     */
    private function setUserType($type)
    {
        $this->userType = $type;
        return $this;
    }

    /**
     * @return string The user object type.
     */
    protected function userType()
    {
        return $this->userType;
    }

    /**
     * @param FactoryInterface $factory The factory used to create new user instances.
     * @return AdminAuthenticator Chainable
     */
    private function setUserFactory(FactoryInterface $factory)
    {
        $this->userFactory = $factory;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    protected function userFactory()
    {
        return $this->userFactory;
    }

    /**
     * @param string $type The auth-token (obj) type.
     * @return AdminAuthenticator Chainable
     */
    private function setTokenType($type)
    {
        $this->tokenType = $type;
        return $this;
    }

    /**
     * @return string The auth-token type.
     */
    protected function tokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param FactoryInterface $factory The factory used to create new auth-token instances.
     * @return AdminAuthenticator Chainable
     */
    private function setTokenFactory(FactoryInterface $factory)
    {
        $this->tokenFactory = $factory;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    protected function tokenFactory()
    {
        return $this->tokenFactory;
    }

    /**
     * @return \Charcoal\User\UserInterface|null The authenticated user object, null if not authenticated.
     */
    public function authenticate()
    {
        $u = $this->authenticateBySession();
        if ($u) {
            return $u;
        }
        $u = $this->authenticateByToken();
        if ($u) {
            return $u;
        }
        return;
    }

    /**
     * Returns the authenticated User, or null if no user match credentials.
     *
     * @param string $username Username, part of necessery credentials.
     * @param string $password Password, part of necessary credentials.
     * @throws InvalidArgumentException If username or password are invalid or empty.
     * @return \Charcoal\User\UserInterface|null
     */
    public function authenticateByPassword($username, $password)
    {
        if (!is_string($username) || !is_string($password)) {
            throw new InvalidArgumentException(
                'Username and password must be strings'
            );
        }

        if ($username == '' || $password == '') {
            throw new InvalidArgumentException(
                'Username and password can not be empty.'
            );
        }

        $u = $this->userFactory()->create($this->userType());

        // Force lowercase
        $username = mb_strtolower($username);

        // Load the user by username
        $u->load($username);

        if ($u->username() != $username) {
            return null;
        }
        if ($u->active() === false) {
            return null;
        }

        // Validate password
        if (password_verify($password, $u->password())) {
            if (password_needs_rehash($u->password(), PASSWORD_DEFAULT)) {
                $this->logger->notice(
                    sprintf('Rehashing password for user "%s" (%s)', $u->username(), $this->userType())
                );
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $u->setPassword($hash);
                $u->update(['password']);
            }

            $u->login();
            return $u;
        } else {
            $this->logger->warning(
                sprintf('Invalid login attempt for user "%s" (%s): invalid password.')
            );
            return null;
        }
    }

    /**
     * @return \Charcoal\User\UserInterface|null
     */
    private function authenticateBySession()
    {
        // Call static method on user
        $u = $this->userFactory()->create($this->userType());
        $u = call_user_func([get_class($u), 'getAuthenticated'], $this->userFactory());
        if ($u && $u->id()) {
            $u->saveToSession();
            return $u;
        } else {
            return null;
        }
    }

    /**
     * @return \Charcoal\User\UserInterface|null
     */
    private function authenticateByToken()
    {
        $tokenType = $this->tokenType();
        $authToken = $this->tokenFactory()->create($tokenType);

        if ($authToken->metadata()->enabled() !== true) {
            return null;
        }

        $tokenData = $authToken->getTokenDataFromCookie();
        if (!$tokenData) {
            return null;
        }
        $username = $authToken->getUsernameFromToken($tokenData['ident'], $tokenData['token']);
        if (!$username) {
            return null;
        }

        $u = $this->userFactory()->create($this->userType());
        $u->load($username);

        if ($u->id()) {
            $u->saveToSession();
            return $u;
        } else {
            return null;
        }
    }
}
