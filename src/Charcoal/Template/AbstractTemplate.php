<?php

namespace Charcoal\Template;

// Module `charcoal-core` dependencies
use \Charcoal\Model\AbstractModel as AbstractModel;
use \Charcoal\View\ViewableInterface as ViewableInterface;
use \Charcoal\View\ViewableTrait as ViewableTrait;

// Local namespace dependencies
use \Charcoal\Template\TemplateInterface as TemplateInterface;

/**
*
*/
abstract class AbstractTemplate implements
    TemplateInterface,
    ViewableInterface
{

    use ViewableTrait;

    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->set_data($data);
        }
    }
//    public function __invoke($re)

    /**
    * @param array $data Optional
    * @return TemplateView
    */
    public function create_view(array $data = null)
    {
        $view = new TemplateView();
        if (is_array($data)) {
            $view->set_data($data);
        }
        return $view;
    }
}
