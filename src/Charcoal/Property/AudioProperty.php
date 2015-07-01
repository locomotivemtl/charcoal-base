<?php

namespace Charcoal\Property;

// From `charcoal-admin`
use \Charcoal\Property\FileProperty as FileProperty;

class AudioProperty extends FileProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'audio';
    }

    /**
    * @return array
    */
    public function accepted_mimetypes()
    {
        return [
            'audio/wav',
            'audio/x-wav'
        ];
    }

    /**
    * @return string
    */
    public function generate_extension()
    {
        $mimetype = $this->mimetype();
        
        $ext = '';
        switch($mimetype) {
            case 'audio/wav':
            case 'audio/x-wav':
                $ext = 'wav';
            break;
        }
        return $ext;
    }

}
