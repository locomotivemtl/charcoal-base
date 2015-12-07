<?php

namespace Charcoal\Property;

use \PDO;

// Intra-module (`charcoal-core`) dependencies
use \Charcoal\Charcoal;
use \Charcoal\Translation\TranslationConfig;

/**
* Language property
*/
class LangProperty extends AbstractProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'lang';
    }

    /**
    * @return string
    */
    public function sql_extra()
    {
        return '';
    }

    /**
    * Get the SQL type (Storage format)
    *
    * Only the 2-character language code (ISO 639-1)
    *
    * @return string The SQL type
    */
    public function sql_type()
    {
        if ($this->multiple()) {
            return 'TEXT';
        }
        return 'CHAR(2)';
    }

    /**
    * @return integer
    */
    public function sql_pdo_type()
    {
        return PDO::PARAM_BOOL;
    }

    /**
    * @return array
    */
    public function choices()
    {
        $translator = TranslationConfig::instance();

        $choices = [];
        foreach ($translator->languages() as $langcode => $langdata) {
            $choices[] = [
                'label'    => (string)$langdata,
                'selected' => ($this->val() === $langcode),
                'value'    => $langcode
            ];
        }

        return $choices;
    }

    /**
    * @return mixed
    */
    public function save()
    {
        return $this->val();
    }
}
