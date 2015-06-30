<?php

namespace Charcoal\Widget;

use \InvalidArgumentException as InvalidArgumentException;

// From `charcoal-core`
use \Charcoal\View\AbstractView as AbstractView;
use \Charcoal\View\ViewTemplateLoader as ViewTemplateLoader;

class WidgetView extends AbstractView
{
    /**
    * @param string $template_ident
    * @throws InvalidArgumentException if the ident is not a string
    * @return string
    */
    public function load_template($template_ident)
    {
        if (!is_string($template_ident)) {
            throw new InvalidArgumentException('Template ident must be a string');
        }
        $template_loader = new ViewTemplateLoader();
        $template = $template_loader->load($template_ident);
        if ($template) {
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
        if (!is_string($context_ident)) {
            throw new \InvalidArgumentException('Context ident must be a string');
        }

        $template_factory = WidgetFactory::instance();
        $template_model = $template_factory->create($context_ident);
        $this->set_context($template_model);

        return $template_model;
    }

    /**
    * @param array $data Optional
    * @return WidgetViewController
    */
    public function create_controller(array $data = null)
    {
        $context = $this->context();
        $controller = new WidgetViewController();
        if (is_array($data)) {
            $controller->set_data($data);
        }
        $controller->set_context($context);
        return $controller;
    }
}
