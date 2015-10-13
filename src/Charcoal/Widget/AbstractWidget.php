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
        foreach ($data as $prop => $val) {
            $func = [$this, 'set_'.$prop];

            if ($val === null) {
                continue;
            }

            if (is_callable($func)) {
                call_user_func($func, $val);
                unset($data[$prop]);
            } else {
                $this->{$prop} = $val;
            }
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
    public function create_view(array $data = null)
    {
         $view = new \Charcoal\View\GenericView([
            //'logger'=>$this->logger()
            'logger'=>null
         ]);
         if ($data !== null) {
             $view->set_data($data);
         }
            return $view;
    }
}
