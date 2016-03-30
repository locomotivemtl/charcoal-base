<?php

namespace Charcoal\User;

/**
 * The `Authorizable` Trait / Interface provides authorization logic and control access to user models.
 */
interface AuthorizableInterface
{
    /**
     * Set user permissions.
     *
     * @param  array|null $permissions The user permissions to set. Either as array or UserPermission objects.
     * @return AuthorizableInterface Chainable
     */
    public function setPermissions($permissions);

    /**
     * Add a user permission.
     *
     * @param  string|array|UserPermissionInterface $ability   The permission identifier, array, or object.
     * @param  array|UserPermissionInterface        $arguments The user permission object/array to add.
     * @return AuthorizableInterface Chainable
     */
    public function addPermission($ability, $arguments = null);

    /**
     * Retrieve user permissions.
     *
     * @return UserPermissionInterface[] The UserPermission list (array) attached to this user.
     */
    public function permissions();

    /**
     * Determine if the entity has a given permission.
     *
     * @param  string|UserPermissionInterface $permission The desired permission.
     * @param  mixed                          $arguments  Options or model(s) related to the tested permission.
     * @return boolean Returns TRUE if the permission is granted, FALSE if denied.
     */
    public function isAllowed($permission, $arguments = null);
}
