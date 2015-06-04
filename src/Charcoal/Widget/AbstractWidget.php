<?php

namespace Charcoal\Widget;

use \InvalidArgumentException as InvalidArgumentException;

// From `charcoal-core`
use \Charcoal\Metadata\DescribableInterface as DescribableInterface;
use \Charcoal\Metadata\DescribableTrait as DescribableTrait;
use \Charcoal\View\ViewableInterface as ViewableInterface;
use \Charcoal\View\ViewableTrait as ViewableTrait;

// From `charcoal-base`
use \Charcoal\Widget\WidgetInterface as WidgetInterface;
use \Charcoal\Widget\WidgetView as Widgetiew;

abstract class AbstractWidget implements
    WidgetInterface,
    //DescribableInterface,
    ViewableInterface
{
    //use DescribableTrait;
    use ViewableTrait;

    /**
    * @var boolean $_active
    */
    private $_active;

    /**
    * @param array $data
    */
    public function __construct($data=null)
    {
        if ($data !== null) {
            $this->set_data($data);
        }
    }

    public function set_data($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Data must be an array');
        }
        $this->set_viewable_data($data);
        if (isset($data['active']) && $data['active'] !== null) {
            $this->set_active($data['active']);
        }
        return $this;
    }

    /**
    * @param boolean $active
    * @throws InvalidArgumentException
    * @return AbstractWidget Chainable
    */
    public function set_active($active)
    {
        if (!is_bool($active)) {
            throw new InvalidArgumentException('Active must be a boolean');
        }
        $this->_active = $active;
        return $this;
    }

    /**
    * @return boolean
    */
    public function active()
    {
        return $this->_active;
    }

    /**
    * ViewableInterface > create_view().
    *
    * @return ViewInterface
    */
    protected function create_view($data=null)
    {
        $view = new WidgetView();
        if ($data !== null) {
            $view->set_data($data);
        }
        return $view;
    }
}

