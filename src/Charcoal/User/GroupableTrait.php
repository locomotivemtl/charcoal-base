<?php

namespace Charcoal\User;

use \Charcoal\User\UserGroupInterface;

/**
 * The `Groupable` Trait / Interface allows a user model to be added to groups.
 *
 * The trait implements the {@see GroupableInterface}.
 */
trait GroupableTrait
{
    /**
     * The parent user groups.
     *
     * @var UserGroupInterface[]
     */
    private $groups;

    /**
     * Set user groups.
     *
     * @param  array|null $groups The user groups this model belongs to.
     * @return GroupableInterface Chainable
     */
    public function setGroups($groups)
    {
        $this->groups = [];

        if (is_array($groups)) {
            $this->addGroups($groups);
        }

        return $this;
    }

    /**
     * Add user groups.
     *
     * @param  array $groups The user groups this model belongs to.
     * @return AuthorizableInterface Chainable
     */
    public function addGroups(array $groups)
    {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        return $this;
    }

    /**
     * Add a user group.
     *
     * @todo   Needs implementation.
     * @param  array|UserGroupInterface $group The group this model belongs to.
     * @return GroupableInterface Chainable
     */
    public function addGroup($group)
    {
        $this->group[] = $group;

        return $this;
    }

    /**
     * Retrieve the groups attached to this user.
     *
     * @return UserGroupInterface[] The UserGroup list (array) attached to this user.
     */
    public function groups()
    {
        return $this->groups;
    }
}
