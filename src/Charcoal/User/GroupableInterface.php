<?php

namespace Charcoal\User;

/**
 * The `Groupable` Trait / Interface allows a user model to be added to groups.
 */
interface GroupableInterface
{
    /**
     * Set user groups.
     *
     * @param  array|null $groups The user groups this model belongs to.
     * @return GroupableInterface Chainable
     */
    public function setGroups($groups);

    /**
     * Add a user group.
     *
     * @param  array|UserGroupInterface $group The group this model belongs to.
     * @return GroupableInterface Chainable
     */
    public function addGroup($group);

    /**
     * Retrieve the groups attached to this user.
     *
     * @return UserGroupInterface[] The UserGroup list (array) attached to this user.
     */
    public function groups();
}
