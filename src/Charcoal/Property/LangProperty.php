<?php

namespace Charcoal\Property;

use \PDO;

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
        $translation_config = new TranslationConfig();
        $langs = $translation_config->available_langs();
        $choices = [];
        foreach ($langs as $lang) {
            $choices[] = [
                'label'     => $lang,
                'selected'  => ($this->val() == $lang),
                'value'     => $lang
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
