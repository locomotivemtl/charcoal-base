Charcoal Base
=============

Additional objects and methods for Charcoal projects.

# How to install

The preferred (and only supported) way of installing _charcoal-app_ is with **composer**:

```shell
★ composer require locomotivemtl/charcoal-app
```

# Dependencies


# Available classes and interfaces

This package extends the _charcoal-core_ framework with more utilities:

- Object
- User
-
Typically, Charcoal projects or modules do not use much of _charcoal-core_ base class, but rather are a collection of `Object` (class and json metadata), either as `UserData` or `Content` and are displayed through a `Template` and its children `Widget` (class and template files). External resources (scripts, styles, images, etc.) are typically included in  project. User input, and some scripts, are provided through `Action`. All those classes are extended from the base classes in this package, _charcoal-base_.

## Action
A response to an event or request. Typically, an operation to be performed on user input (submit form, etc.) or from system (cron, etc.) The `\Charcoal\Action` class ensures standards across request handling, response output, etc.

## Email
The `\Charcoal\Email` namespace contains everything required to send, log, queue and track emails as well as generate them from templates.

## Image
Image manipulation. This is still @todo (port from _charcoal-legacy_). The plan is to switch to [http://image.intervention.io/].

## Module
...

## Object
...

## Property
The core Property classes and interfaces are found in `charcoal-core`, however, basic concrete properties are provided within this package:
- Boolean
- Choice
  - Object
- Color
- Date
  - Date
  - DateTime
  - Day
  - Month
  - Time
  - Year
- File
  - Image
  - Video
- Number
  - Float
  - Integer
- Id
- Image
- String
  - Html
  - Password
  - Text
- Structure

## Template
Templates are renderable top-level models, through _charcoal-core_'s `ViewableInterface`. They are typicaly consisted of a Template class and an actual template file (mustache or php).

> Although it is perfectly fine to use `Charcoal\Template` directly, most projects will use the specialized `Charcoal\Cms\Template` from the _charcoal-cms` module.

### Example: binding a template to a URL (route)
``` php
Charcoal::app()->get('/example', function($args=null) {
    $view = new TemplateView();
    $content = $view->from_ident('charcoal/project/template/example')->render();
    echo $content;
  })->name('/example');
```

## Widget
...

# Authors
- Mathieu Ducharme, mat@locomotive.ca

# License
- TBA

# Release History
_Unreleased_


