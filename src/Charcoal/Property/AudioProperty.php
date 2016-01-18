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
     * @var integer $_minLength
     */
    private $minLength = 0;

    /**
     * Maximum audio length, in seconds.
     * @var integer $_maxLength
     */
    private $maxLength = 0;

    /**
     * @return string
     */
    public function type()
    {
        return 'audio';
    }

    /**
     * @param integer $minLength The minimum length allowed, in seconds.
     * @throws InvalidArgumentException If the length is not an integer.
     * @return AudioProperty Chainable
     */
    public function setMinLength($minLength)
    {
        if (!is_int($minLength)) {
            throw new InvalidArgumentException(
                'Min length must be an integer (in seconds)'
            );
        }
        $this->minLength = $minLength;
        return $this;
    }

    /**
     * @return integer
     */
    public function minLength()
    {
        return $this->minLength;
    }

    /**
     * @param integer $maxLength The maximum length allowed, in seconds.
     * @throws InvalidArgumentException If the length is not an integer.
     * @return AudioProperty Chainable
     */
    public function setMaxLength($maxLength)
    {
        if (!is_int($maxLength)) {
            throw new InvalidArgumentException(
                'Max length must be an integer (in seconds)'
            );
        }
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return integer
     */
    public function maxLength()
    {
        return $this->maxLength;
    }

    /**
     * @return array
     */
    public function acceptedMimetypes()
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
    public function generateExtension()
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
