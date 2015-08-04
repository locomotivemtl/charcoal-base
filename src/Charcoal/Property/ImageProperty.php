<?php

namespace Charcoal\Property;

// Local namespace dependencies
use \Charcoal\Property\AbstractProperty as AbstractProperty;

/**
* Image Property.
*
* The image property is a specialized file property.
*/
class ImageProperty extends FileProperty
{

    /**
    * @var arrat $_effects
    */
    private $_effects = [];

    /**
    * @return string
    */
    public function type()
    {
        return 'image';
    }

    /**
    * @param array $data
    * @return AudioProperty Chainable
    */
    public function set_data(array $data)
    {

        parent::set_data($data);

        if (isset($data['effects']) && $data['effects'] !== null) {
            $this->set_effects($data['effects']);
        }
        return $this;
    }

    /**
    * @return array
    */
    public function accepted_mimetypes()
    {
        return [
            'image/gif',
            'image/jpg',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/svg+xml'
        ];
    }

    /**
    * @return string
    */
    public function generate_extension()
    {
        $mimetype = $this->mimetype();
        
        $ext = '';
        switch ($mimetype) {
            case 'image/gif':
                $ext = 'gif';
                break;

            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $ext = 'jpg';
                break;

            case 'image/png':
                $ext = 'png';
                break;

            case 'image/svg+xml':
                $ext = 'svg';
                break;
        }
        return $ext;
    }
}
