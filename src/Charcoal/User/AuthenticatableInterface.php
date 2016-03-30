<?php

namespace Charcoal\User;

/**
 *
 */
interface AuthenticatableInterface
{
    /**
     * @param  string $username Username.
     * @param  string $password Password.
     * @return boolean Login success / failure.
     */
    public function authenticate($username, $password);
}
