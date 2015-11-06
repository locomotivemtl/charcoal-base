<?php

namespace Charcoal\Object;

/**
*
*/
trait RoutableTrait
{
    /**
    * @var string $url
    */
    private $url;

    /**
    * @var string
    */
    private $url_pattern = '';

    /**
    * @param mixed $url
    * @return RoutableInterface Chainable
    */
    public function set_url($url)
    {
        $this->url = $url;
    }

    /**
    * @return string
    */
    public function url()
    {
        if ($this->url === null) {
            $this->url = $this->generate_url();
        }
        return $this->url;
    }

    /**
    * Generate a URL from the object's URL pattern.
    */
    public function generate_url()
    {
        $pattern = $this->url_pattern();
        // @todo
        return $patern;
    }

    /**
    * @param mixed $url
    * @return RoutableInterface Chainable
    */
    public function set_url_pattern($url)
    {
        $this->url_pattern = $url;
        return $this;
    }

    /**
    * @return string
    */
    public function url_pattern()
    {
        return $this->url_pattern;
    }
}
