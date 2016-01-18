<?php

namespace Charcoal\Property;

// From `charcoal-image`
use \Charcoal\Image\ImageFactory;

// Local namespace dependencies
use \Charcoal\Property\AbstractProperty as AbstractProperty;

/**
 * Image Property.
 *
 * The image property is a specialized file property that stores image file.
 */
class ImageProperty extends FileProperty
{

    /**
     * @var array $effects
     */
    private $effects = [];

    /**
     * @return string
     */
    public function type()
    {
        return 'image';
    }

    /**
     * Set (reset, in fact) the image effects.
     *
     * @param array $effects The effects to set to the image.
     * @return ImageProperty Chainable
     */
    public function setEffects(array $effects)
    {
        $this->effects = [];
        foreach ($effects as $effect) {
            $this->addEffect($effect);
        }
        return $this;
    }

    /**
     * @param mixed $effect An image effect.
     * @return ImageProperty Chainable
     */
    public function addEffect($effect)
    {
        $this->effects[] = $effect;
        return $this;
    }

    /**
     * @return array
     */
    public function effects()
    {
        return $this->effects;
    }

    /**
     * @return array
     */
    public function acceptedMimetypes()
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
    public function generateExtension()
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

    /**
     * Add effects to file upload
     *
     * @param array $fileData The file data to upload.
     * @return string
     * @see FileProperty::file_upload()
     */
    public function fileUpload(array $fileData)
    {
        $target = parent::fileUpload($fileData);

        $effects = $this->effects();
        if (!empty($effects)) {
            // @todo Save original file here
            $imageFactory = new ImageFactory();
            $img = $imageFactory->create('imagemagick');
            $img->open($target);
            $img->setEffects($effects);
            $img->proccess();
            $img->save();
        }

        return $target;
    }
}
