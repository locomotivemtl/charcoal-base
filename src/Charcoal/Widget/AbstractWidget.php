<?php

namespace Charcoal\Widget;

// Dependencies from `PHP`
use \InvalidArgumentException as InvalidArgumentException;

// Module `charcoal-core` dependencies
use \Charcoal\Metadata\DescribableInterface as DescribableInterface;
use \Charcoal\Metadata\DescribableTrait as DescribableTrait;
use \Charcoal\View\ViewableInterface as ViewableInterface;
use \Charcoal\View\ViewableTrait as ViewableTrait;

// Local namespace dependencies
use \Charcoal\Widget\WidgetInterface as WidgetInterface;
use \Charcoal\Widget\WidgetView as Widgetiew;

/**
*
*/
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
    * @param array $data Optional
    */
    public function __construct(array $data = null)
    {
        if (is_array($data)) {
            $this->set_data($data);
        }
    }

    /**
    * @param array $data
    * @return AbstractWidget Chainable
    */
    public function set_data(array $data)
    {
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
    * @param array $data Optional
    * @return ViewInterface
    */
    protected function create_view(array $data = null)
    {
        $view = new WidgetView();
        if (is_array($data)) {
            $view->set_data($data);
        }
        return $view;
    }
}
