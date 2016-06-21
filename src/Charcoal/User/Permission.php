<?php

namespace Charcoal\User;

// Module `charcoal-core` dependencies
use \Charcoal\Model\AbstractModel;

// Module `charcoal-base` dependencies
use \Charcoal\Object\CategorizableInterface;
use \Charcoal\Object\CategorizableTrait;

// Module `charcoal-translation` dependencies
use \Charcoal\Translation\TranslationString;

/**
 * A permission is a simple string, that can be read with additional data (name + category) from storage.
 */
class Permission extends AbstractModel implements CategorizableInterface
{
    use CategorizableTrait;

    /**
     * @var string $ident
     */
    public $ident;

    /**
     * @var TranslationString $name
     */
    private $name;

    /**
     * Permission can be used as a string (ident).
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->ident;
    }

    /**
     * @param mixed $name The permission name / label.
     * @return Permission Chainable
     */
    public function setName($name)
    {
        $this->name = new TranslationString($name);
        return $this;
    }

    /**
     * @return TranslationString
     */
    public function name()
    {
        return $this->name;
    }
}
