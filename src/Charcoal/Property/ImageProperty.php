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
    public function set_effects(array $effects)
    {
        $this->effects = [];
        foreach ($effects as $effect) {
            $this->add_effect($effect);
        }
        return $this;
    }

    /**
     * @param mixed $effect An image effect.
     * @return ImageProperty Chainable
     */
    public function add_effect($effect)
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

    /**
     * Add effects to file upload
     *
     * @param array $file_data The file data to upload.
     * @return string
     * @see FileProperty::file_upload()
     */
    public function file_upload(array $file_data)
    {
        $target = parent::file_upload($file_data);

        $effects = $this->effects();
        if (!empty($effects)) {
            // @todo Save original file here
            $image_factory = new ImageFactory();
            $img = $image_factory->create('imagemagick');
            $img->open($target);
            $img->set_effects($effects);
            $img->proccess();
            $img->save();
        }

        return $target;
    }
}
