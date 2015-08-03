Charcoal Template
=================

Templates are renderable top-level models, through _charcoal-core_'s `ViewableInterface`. They are typicaly consisted of a Template class and an actual template file (mustache or php). 

# Usage

Using View directly, render a template from an "ident":
```php
use \Charcoal\Template\TemplateView;
$view = new TemplateView();
$view->from_ident('namespace/project/template/name');
echo $view->render();
```

Using a Template model directly:
```php
$tpl = new \Namespace\Project\Template\Name();
$tpl->set_data([
    'title'=>'Title'
]);
echo $tpl->render();
```

Using the TemplateFactory instead:
```php
use \Charcoal\Template\TemplateFactory;
$tpl = TemplateFactory::instance()->create('namespace/project/template/name');
echo $tpl->render();
```
