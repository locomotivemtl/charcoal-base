<?php

namespace Charcoal\Template;

use \Charcoal\View\AbstractView as AbstractView;

use \Charcoal\Template\TemplateFactory as TemplateFactor;
use \Charcoal\Template\TemplateLoader as TemplateLoader;

/**
* Template View
*/
class TemplateView extends AbstractView
{
    /**
    * @param string $template_ident
    * @throws \InvalidArgumentException if the ident is not a string
    * @return string
    */
    public function load_template($template_ident)
    {
        if(!is_string($template_ident)) {
            throw new \InvalidArgumentException('Template ident must be a string');
        }

        $template_loader = new TemplateLoader();
        $template = $template_loader->load($template_ident);
        if($template) {
            $this->set_template($template);
        }

        return $template;
    }

    /**
    * @param string $context_ident
    * @throws \InvalidArgumentException if the ident is not a string
    * @return mixed
    */
    public function load_context($context_ident)
    {
        if(!is_string($context_ident)) {
            throw new \InvalidArgumentException('Context ident must be a string');
        }

        $template_factory = TemplateFactory::instance();
        $template_model = $template_factory->create($context_ident);
        $this->set_context($template_model);

        return $template_model;
    }

    public function create_controller($data=null)
    {
        $context = $this->context();
        $controller = new TemplateViewController();
        if($data !== null) {
            $controller->set_data($data);
        }
        $controller->set_context($context);
        return $controller;
    }
}
