Charcoal Base
=============

The `charcoal-base` module provides two _core_ Charcoal namespaces: `\Charcoal\Object` and `\Charcoal\User`.

Both namespaces are planned to move into their own module in the near future. Therefore, this module is unreleased (install with `dev-master`).

# Table of content

- [How to install](#how-to-install)
	+ [Dependencies](#dependencies)
	+ [Recommended modules](#recommended-modules)
- [The `\Charcoal\Object` namespace](#the-charcoal-object-namespace)
	+ [Basic classes](#basic-classes)
		- [Content](#content)
		- [UserData](#userdata)
	+ [Object behaviors](#object-behaviors)
		- [Archivable](#archivable)
		- [Categorizable](#categorizable)
		- [Category](#category)
		- [Hierarchical](#hierarchical)
		- [Publishable](#publishable)
		- [Revisionable](#revisionable)
		- [Routable](#routable)
	+ [Helpers](#helpers)
		- [ObjectRevision](#objectrevision)
		- [ObjectSchedule](#objectschedule)
- [Development](#development)
	+ [Development dependencies](#development-dependencies)
	+ [Continuous Integration](#continuous-integration)
	+ [Coding Style](#coding-style)
	+ [Authors](#authors)
	+ [Changelog](#changelog)

# How to install

The preferred (and only supported) way of installing _charcoal-base is with **composer**:

```shell
★ composer require locomotivemtl/charcoal-base
```

## Dependencies

- PHP 5.5+
	+ This is the last supported version of PHP.
	+ `PHP 7` is also supported (meaning _green on travis_...).
- `locomotivemtl/charcoal-core`
	+ Base objects are depdendent on `\Charcoal\Model\AbstractModel`.
- `locomotivemtl/charcoal-translation`
	+ Certain object properties are translatable (`TranslationString`).

## Recommended modules

In addition to the above dependencies, here's a list of recommended modules that can be added to a project.

- [locomotivemtl/charcoal-cms](https://github.com/locomotivemtl/charcoal-cms)
	- Base objects (Section, Text, Document, etc.) for a _CMS_ style of website.
	- Provide additional object behaviors for content.
	- Is made to be used with charcoal-admin
- [locomotivemtl/charcoal-admin](https://github.com/locomotivemtl/charcoal-admin)
	- A modern, responsive backend for Charcoal projects.
	- Especially made for Charcoal _models_ / _objects_.charcoal-base

For a complete, ready-to-use project, start from the [`boilerplate`](https://github.com/locomotivemtl/charcoal-project-boilerplate):

```shell
★ composer create-project locomotivemtl/charcoal-project-boilerplate
```


# The `\Charcoal\Object` namespace

The `\Charcoal\Object` namespace provides a bunch of basic classes, helpers as well as object behaviors (interfaces + traits).

## Basic classes

All charcoal project object classes should extend one of the 2 base classes, [`\Charcoal\Object\Content`](#content) or [`\Charcoal\Object\UserData`](#userdata).

### Content

The **Content** base class should be used for all objects which can be "managed". Typically by an administrator, via the `charcoal-admin` module. It adds the "active" flag to objects as well as creation and modification informations.

**API**

- `	setActive($active)`
- `active()`
- `setPosition($position)`
- `position()`
- `setCreated($created)`
- `created()`
- `setCreatedBy($createdBy)`
- `createdBy()`
- `setLastModified($lastModified)`
- `lastModified()`
- `setLastModifiedBy($lastModifiedBy)`
- `lastModifiedBy()`

> The `Content` class extends `\Charcoal\Model\AbstractModel` from the `charcoal-core` module, which means that it also inherits its API as well as the `DescribableInterface` (`metadata()`, `setMetadata()` and `loadMetadata()`, amongst others) and the `StorableInterface` (`id()`, `key()`, `save()`, `update()`,  `delete()`, `load()`, `loadFrom()`, `loadFromQuery()`, `source()` and `setSource()`, amongst others).
>
> The `AbstractModel` class extends `\Charcoal\Config\AbstractEntity` which also defines basic data-access methods (`setData()`, `data()`, `keys()`, `has()`, `get()`, `set()`, plus the `ArrayAccess`, `JsonSerializable` and `Serializable` interfaces).

**Properties (metadata)**

| Property               | Type        | Default     | Description |
| ---------------------- | ----------- | ----------- | ----------- |
| **active**             | `boolean`   | `true`      | ...         |
| **position**           | `number`    | `null`      | ...        |
| **created**            | `date-time` | `null` [1]  | ...         |
| **created_by**         | `string`    | `''` [1]    | ...         |
| **last_modified**      | `date-time` | `null` [2]  | ...         |
| **last\_modified\_by** | `string`    | `''` [2]    | ...         |

<small>[1] Auto-generated upon `save()`</small><br>
<small>[2] Auto-generated upon `update()`</small><br>

> Default metadata is defined in `metadata/charcoal/object/content.json`

### UserData

The **UserData** class should be used for all objects that are expected to be entered from the project's "client" or "end user".

**API**

- `setIp($ip)`
- `ip()`
- `setTs($ts)`
- `ts()`
- `setLang($lang)`
- `lang()`

> The `Content` class extends `\Charcoal\Model\AbstractModel` from the `charcoal-core` module, which means that it also inherits its API as well as the `DescribableInterface` (`metadata()`, `setMetadata()` and `loadMetadata()`, amongst others) and the `StorableInterface` (`id()`, `key()`, `save()`, `update()`,  `delete()`, `load()`, `loadFrom()`, `loadFromQuery()`, `source()` and `setSource()`, amongst others).
>
> The `AbstractModel` class extends `\Charcoal\Config\AbstractEntity` which also defines basic data-access methods (`setData()`, `data()`, `keys()`, `has()`, `get()`, `set()`, plus the `ArrayAccess`, `JsonSerializable` and `Serializable` interfaces).

**Properties (metadata)**

| Property  | Type        | Default     | Description |
| --------- | ----------- | ----------- | ----------- |
| **ip**    | `ip`        | `null` [1]  | ...         |
| **ts**    | `date-time` | `null` [1]  | ...         |
| **lang**  | `lang`      | `null` [1]  | ...         |

<small>[1] Auto-generated upon `save()` and `update()`</small><br>

> Default metadata is defined in `metadata/charcoal/object/user-data.json`

##Object behaviors

- [Archivable](#archivable)
- [Categorizable](#categorizable)
- [Category](#category)
- [Hierarchical](#hierarchical)
- [Publishable](#publishable)
- [Revisionable](#revisionable)
- [Routable](#routable)

### Archivable

_The archivable behavior is not yet documented. It is still under heavy development._

### Categorizable

**API**

- `setCategory($category)`
- `category()`
- `setCategoryType($type)`
- `categoryType()`

**Properties (metadata)**

| Property        | Type       | Default     | Description |
| --------------- | ---------- | ----------- | ----------- |
| **category**    | `object`   | `null`      | The object's category.[1] |

<small>[1] The category `obj_type` must be explicitely set in implementation's metadata.</small>

> Default metadata is defined in `metadata/charcoal/object/catgorizable-interface.json`

### Category

**API**

- `setCategoryItemType($type)`
- `categoryItemType()`
- `numCategoryItems()`
- `hasCategoryItems()`
- `categoryItems()`

**Properties (metadata)**

| Property        | Type       | Default     | Description |
| --------------- | ---------- | ----------- | ----------- |
| **category_item**    | `string`   | `null`      | ... |

> Default metadata is defined in `metadata/charcoal/object/catgory-interface.json`

### Hierarchical

**API**

- `hasMaster()`
- `isTopLevel()`
- `isLastLevel()`
- `hierarchyLevel()`
- `master()`
- `toplevelMaster()`
- `hierarchy()`
- `invertedHierarchy()`
- `isMasterOf($child)`
- `recursiveIsMasterOf($child)`
- `hasChildren()`
- `numChildren()`
- `recursiveNumChildren()`
- `children()`
- `isChildOf($master)`
- `recursiveIsChildOf($master)`
- `hasSiblings()`
- `numSiblings()`
- `siblings()`
- `isSiblingOf($sibling)`

**Properties (metadata)**

| Property      | Type       | Default     | Description |
| ------------- | ---------- | ----------- | ----------- |
| **master**    | `object`   | `null`      | The master object (parent in hierarchy). |

> Default metadata is defined in `metadata/charcoal/object/hierarchical-interface.json`.

### Publishable


- `setPublishDate($publishDate)`
- `publishDate()`
- `setExpiryDate($expiryDate)`
- `expiryDate()`
- `setPublishStatus($status)`
- `publishStatus()`
- `isPublished()`

**Properties (metadata)**

| Property           | Type         | Default    | Description |
| ------------------ | ------------ | ---------- | ----------- |
| **publish_date**   | `date-time`  | `null`     | ... |
| **expiry_date**    | `date-time`  | `null`     | ... |
| **publish_status** | `string` [1] | `'draft'`  | ... |

> Default metadata is defined in `metadata/charcoal/object/publishable-interface.json`.

### Revisionable

**API**

- `setRevisionEnabled($enabled)`
- `revisionEnabled()`
- `revisionObject()`
- `generateRevision()`
- `latestRevision()`

**Properties (metadata)**

_The revisionable behavior does not implement any properties._

### Routable

_The routable behavior is not yet documented. It is still under heavy development._

## Helpers

###ObjectRevision

### ObjetSchedule

# Development

To install the development environment:

```shell
★ composer install --prefer-source
```

To run the scripts (phplint, phpcs and phpunit):

```shell
★ composer test
```

## API documentation

- The auto-generated `phpDocumentor` API documentation is available at [https://locomotivemtl.github.io/charcoal-base/docs/master/](https://locomotivemtl.github.io/charcoal-base/docs/master/)
- The auto-generated `apigen` API documentation is available at [https://codedoc.pub/locomotivemtl/charcoal-base/master/](https://codedoc.pub/locomotivemtl/charcoal-base/master/index.html)

## Development dependencies

- `phpunit/phpunit`
- `squizlabs/php_codesniffer`
- `satooshi/php-coveralls`

## Continuous Integration

| Service | Badge | Description |
| ------- | ----- | ----------- |
| [Travis](https://travis-ci.org/locomotivemtl/charcoal-base) | [![Build Status](https://travis-ci.org/locomotivemtl/charcoal-base.svg?branch=master)](https://travis-ci.org/locomotivemtl/charcoal-base) | Runs code sniff check and unit tests. Auto-generates API documentation. |
| [Scrutinizer](https://scrutinizer-ci.com/g/locomotivemtl/charcoal-base/) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/locomotivemtl/charcoal-base/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/locomotivemtl/charcoal-base/?branch=master) | Code quality checker. Also validates API documentation quality. |
| [Coveralls](https://coveralls.io/github/locomotivemtl/charcoal-base) | [![Coverage Status](https://coveralls.io/repos/github/locomotivemtl/charcoal-base/badge.svg?branch=master)](https://coveralls.io/github/locomotivemtl/charcoal-base?branch=master) | Unit Tests code coverage. |
| [Sensiolabs](https://insight.sensiolabs.com/projects/533b5796-7e69-42a7-a046-71342146308a) | [![SensioLabsInsight](https://insight.sensiolabs.com/projects/533b5796-7e69-42a7-a046-71342146308a/mini.png)](https://insight.sensiolabs.com/projects/533b5796-7e69-42a7-a046-71342146308a) | Another code quality checker, focused on PHP. |

## Coding Style

The charcoal-base module follows the Charcoal coding-style:

- [_PSR-1_](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
- [_PSR-2_](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
- [_PSR-4_](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md), autoloading is therefore provided by _Composer_.
- [_phpDocumentor_](http://phpdoc.org/) comments.
- Read the [phpcs.xml](phpcs.xml) file for all the details on code style.

> Coding style validation / enforcement can be performed with `composer phpcs`. An auto-fixer is also available with `composer phpcbf`.

# Authors

- Mathieu Ducharme, mat@locomotive.ca

# Changelog

_Unreleased_

# License

**The MIT License (MIT)**

_Copyright © 2016 Locomotive inc._
> See [Authors](#authors).

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
