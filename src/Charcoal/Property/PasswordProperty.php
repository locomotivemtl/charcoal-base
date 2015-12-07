<?php

namespace Charcoal\Property;

// Local namespace dependencies
use \Charcoal\Property\StringProperty as StringProperty;

/**
* Password Property
*
* The password property is a specialized string property meant to store encrypted passwords.
*/
class PasswordProperty extends StringProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'password';
    }

    public function save()
    {
        $pw_opts = ['cost'=>12];
        $password = $this->val();
        $val = $this->val();

        // Assuming the password_needs_rehash is set to true is the hash given isn't a hash
        if (password_needs_rehash($password, PASSWORD_DEFAULT, $pw_opts)) {
            $val = password_hash($password, PASSWORD_DEFAULT, $pw_opts);
            $this->set_val($val);
        }

        return $val;
    }
}
