<?php

namespace Charcoal\User;

/**
 * The `Authorizable` Trait / Interface provides authorization logic and control access to user models.
 *
 * The trait implements the {@see AuthorizableInterface}.
 */
trait AuthorizableTrait
{
    /**
     * The defined user permissions.
     *
     * @var \Charcoal\User\UserPermissionInterface[]
     */
    private $permissions;

    /**
     * Set user permissions.
     *
     * Existing permissions will be lost; to append more permissions use {@see self::addPermissions()}.
     *
     * @param  array|null $permissions The user permissions to set. Either as array or UserPermission objects.
     * @return AuthorizableInterface Chainable
     */
    public function setPermissions($permissions)
    {
        $this->permissions = [];

        if (is_array($permissions)) {
            $this->addPermissions($permissions);
        }

        return $this;
    }

    /**
     * Add user permissions.
     *
     * @param  array $permissions The user permissions to set. Either as array or UserPermission objects.
     * @return AuthorizableInterface Chainable
     */
    public function addPermissions(array $permissions)
    {
        foreach ($permissions as $ability) {
            $this->addPermission($ability);
        }

        return $this;
    }

    /**
     * Add a user permission.
     *
     * @todo   Needs implementation. Maybe like {@see \Charcoal\Source\AbstractSource\addFilter()}.
     * @param  string|array|\Charcoal\User\UserPermissionInterface $ability   The permission identifier, array, or object.
     * @param  array|\Charcoal\User\UserPermissionInterface        $arguments The user permission object/array to add.
     * @return AuthorizableInterface Chainable
     */
    public function addPermission($ability, $arguments = null)
    {
        if (isset($arguments)) {
            $permission = $arguments;
        } else {
            $permission = $ability;
        }

        $this->permissions[] = $permission;

        return $this;
    }

    /**
     * Retrieve user permissions.
     *
     * @return \Charcoal\User\UserPermissionInterface[] The UserPermission list (array) attached to this user.
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * Determine if the entity has a given permission.
     *
     * @todo   Needs implementation.
     * @param  string|\Charcoal\User\UserPermissionInterface $permission The desired permission.
     * @param  mixed                                         $arguments  Options or model(s) related to the tested permission.
     * @return boolean Returns TRUE if the permission is granted, FALSE if denied.
     */
    public function isAllowed($permission, $arguments = null)
    {
        unset($arguments);

        return array_search($permission, $this->permissions);
    }
}
