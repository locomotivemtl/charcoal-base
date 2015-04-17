<?php

namespace Charcoal\Template;

// From `charcoal-core`
use \Charcoal\Model\AbstractModel as AbstractModel;
use \Charcoal\View\ViewableInterface as ViewableInterface;
use \Charcoal\View\ViewableTrait as ViewableTrait;

// From `charcoal-base`
use \Charcoal\Template\TemplateInterface as TemplateInterface;

abstract class AbstractTemplate extends AbstractModel implements
    TemplateInterface
{
    public function create_view($data=null)
    {
        $view = new TemplateView();
        if($data !== null) {
            $view->set_data($data);
        }
        return $view;
    }
}
