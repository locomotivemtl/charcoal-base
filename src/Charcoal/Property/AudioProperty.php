<?php

namespace Charcoal\Property;

use \InvalidArgumentException as InvalidArgumentException;

// From `charcoal-admin`
use \Charcoal\Property\FileProperty as FileProperty;

class AudioProperty extends FileProperty
{


    /**
    * Minimum audio length, in seconds.
    * @var integer $_min_length
    */
    private $_min_length = 0;

    /**
    * Maximum audio length, in seconds.
    * @var integer $_max_length
    */
    private $_max_length = 0;

    /**
    * @return string
    */
    public function type()
    {
        return 'audio';
    }

    /**
    * @param array $data
    * @return AudioProperty Chainable
    */
    public function set_data(array $data)
    {

        parent::set_data($data);

        if (isset($data['min_length']) && $data['min_length'] !== null) {
            $this->set_min_length($data['min_length']);
        }
        if (isset($data['max_length']) && $data['max_length'] !== null) {
            $this->set_max_length($data['max_length']);
        }
        return $this;
    }

    /**
    * @param integer $min_length
    * @throws InvalidArgumentException
    * @return AudioProperty Chainable
    */
    public function set_min_length($min_length)
    {
        if (!is_int($min_length)) {
            throw new InvalidArgumentException('Min length must be an integer (in seconds)');
        }
        $this->_min_length = $min_length;
        return $this;
    }

    /**
    * @return integer
    */
    public function min_length()
    {
        return $this->_min_length;
    }

    /**
    * @param integer $max_length
    * @throws InvalidArgumentException
    * @return AudioProperty Chainable
    */
    public function set_max_length($max_length)
    {
        if (!is_int($max_length)) {
            throw new InvalidArgumentException('Max length must be an integer (in seconds)');
        }
        $this->_max_length = $max_length;
        return $this;
    }

    /**
    * @return integer
    */
    public function max_length()
    {
        return $this->_max_length;
    }

    /**
    * @return array
    */
    public function accepted_mimetypes()
    {
        return [
            'audio/mpeg',
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
            case 'audio/mpeg':
                $ext = 'mp3';
            break;

            case 'audio/wav':
            case 'audio/x-wav':
                $ext = 'wav';
            break;
        }
        return $ext;
    }

}
