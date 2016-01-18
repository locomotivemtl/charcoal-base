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
    public function sqlExtra()
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
    public function sqlType()
    {
        if ($this->multiple()) {
            return 'TEXT';
        }
        return 'CHAR(2)';
    }

    /**
     * @return integer
     */
    public function sqlPdoType()
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
        foreach ($translator->languages() as $langCode) {
            $language = $translator->language($langCode);
            $choices[] = [
                'label'    => (string)$language,
                'selected' => ($this->val() === $langCode),
                'value'    => $langCode
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
