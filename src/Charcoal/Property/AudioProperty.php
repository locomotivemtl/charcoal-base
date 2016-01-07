<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \InvalidArgumentException;

// Local namespace dependencies
use \Charcoal\Property\FileProperty;

/**
 * Audio Property.
 *
 * The audio property is a specialized file property.
 */
class AudioProperty extends FileProperty
{
    /**
     * Minimum audio length, in seconds.
     * @var integer $_min_length
     */
    private $min_length = 0;

    /**
     * Maximum audio length, in seconds.
     * @var integer $_max_length
     */
    private $max_length = 0;

    /**
     * @return string
     */
    public function type()
    {
        return 'audio';
    }

    /**
     * @param integer $min_length The minimum length allowed, in seconds.
     * @throws InvalidArgumentException If the length is not an integer.
     * @return AudioProperty Chainable
     */
    public function set_min_length($min_length)
    {
        if (!is_int($min_length)) {
            throw new InvalidArgumentException(
                'Min length must be an integer (in seconds)'
            );
        }
        $this->min_length = $min_length;
        return $this;
    }

    /**
     * @return integer
     */
    public function min_length()
    {
        return $this->min_length;
    }

    /**
     * @param integer $max_length The maximum length allowed, in seconds.
     * @throws InvalidArgumentException If the length is not an integer.
     * @return AudioProperty Chainable
     */
    public function set_max_length($max_length)
    {
        if (!is_int($max_length)) {
            throw new InvalidArgumentException(
                'Max length must be an integer (in seconds)'
            );
        }
        $this->max_length = $max_length;
        return $this;
    }

    /**
     * @return integer
     */
    public function max_length()
    {
        return $this->max_length;
    }

    /**
     * @return array
     */
    public function accepted_mimetypes()
    {
        return [
            'audio/mp3',
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
        switch ($mimetype) {
            case 'audio/mp3':
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
