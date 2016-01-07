<?php

namespace Charcoal\User;

// From `charcoal-config`
use \Charcoal\Config\AbstractConfig;

/**
 *
 */
class UserConfig extends AbstractConfig
{
    /**
     * @var array $subscription_email
     */
    private $subscription_email;

    /**
     * @var array $lost_password_email
     */
    private $lost_password_email;
}
