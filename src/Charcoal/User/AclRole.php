<?php

namespace Charcoal\User;

use \Charcoal\Model\AbstractModel;

/**
 * ACL Roles define hierarchical allowed and denied permissions.
 *
 * They can be attached to user accounts for fine-grained permission control.
 */
class AclRole extends AbstractModel
{
    /**
     * @var string $ident
     */
    public $ident;

    /**
     * The parent ACL role.
     *
     * This role will inherit all of its parent's permissions.
     *
     * @var string $parent
     */
    public $parent;

    /**
     * List of explicitely allowed permissions.
     *
     * @var string[] $allowed
     */
    public $allowed;

    /**
     * List of explicitely denied permissions.
     *
     * @var string[] $denied
     */
    public $denied;

    /**
     * @var boolean
     */
    private $superuser;

    /**
     * @var integer
     */
    private $position;

    /**
     * ACL Role can be used as a string (ident).
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->ident;
    }

    /**
     * @param boolean $isSuper The superuser flag.
     * @return AclRole Chainable
     */
    public function setSuperuser($isSuper)
    {
        $this->superuser = !!$isSuper;
        return $this;
    }

    /**
     * @return boolean
     */
    public function superuser()
    {
        return $this->superuser;
    }

    /**
     * @param integer $position The role's ordering position.
     * @return AclRole Chainable
     */
    public function setPosition($position)
    {
        $this->position = (int)$position;
        return $this;
    }

    /**
     * @return integer
     */
    public function position()
    {
        return $this->position;
    }
}
