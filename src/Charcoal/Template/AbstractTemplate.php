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
}
