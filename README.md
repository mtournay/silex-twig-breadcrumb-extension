# Aim of this fork
This fork was intended to keep compatibility with silex 2.3, without dependencies from symfony 4
I also add some minor functionnality

# Twig breadcrumb extension for Silex
This is a breadcrumb extension for Twig which includes a breadcrumb service provider for silex for easy and simple use
of breadcrumbs in Silex.

[![Packagist](https://img.shields.io/packagist/v/nymo/silex-twig-breadcrumb-extension.svg)](https://packagist.org/packages/nymo/silex-twig-breadcrumb-extension)
[![Monthly Downloads](https://poser.pugx.org/nymo/silex-twig-breadcrumb-extension/d/monthly)](https://packagist.org/packages/nymo/silex-twig-breadcrumb-extension)
[![Build Status](https://travis-ci.org/nymo/silex-twig-breadcrumb-extension.svg?branch=master)](https://travis-ci.org/nymo/silex-twig-breadcrumb-extension)
[![Coverage Status](https://coveralls.io/repos/github/nymo/silex-twig-breadcrumb-extension/badge.svg?branch=master)](https://coveralls.io/github/nymo/silex-twig-breadcrumb-extension?branch=master)
## New in Version 3.0
- Support for PHP > 7.1
- Package is using now PSR-4 autoloader
- Method *addItem* in BreadCrumbCollection is now deprecated and will be removed in 3.1 use *addSimpleItem* or *addRouteItem* instead
[see below](#usage)
## Roadmap
Since Silex get EOL in June 2018 this Extension will be archived, too. No further features will be implemented.
**I'm going to migrate the library to Symfony 4 as this is the future road for all Silex applications.**

## General Features
- Create easily breadcrumbs in your Silex application
- i18n support
- Configurable separator
- Configurable Template
- Template override
- Named route support

## General Requirements
- Twig
- gettext must be activated in your PHP environment for i8n support since version 1.1.0

### For PHP 5.3
- Silex 1.x
- silex-twig-breadcrumb-extension in Version 1.x

### For PHP 5.6
- Silex 2.x
- silex-twig-breadcrumb-extension in Version 2.x

### For PHP > 7.1
- Silex 2.x
- silex-twig-breadcrumb-extension in Version 3.x


## Installation

### PHP Configuration
Please make sure that gettext functionality is activated in your PHP environment. For further assistance please refer
to the official PHP Manual http://www.php.net

### Via composer:
First add the following to your composer.json file
```
"require":{
        "nymo/silex-twig-breadcrumb-extension":"~3.0"
    }
```

Use Version 2.x for Silex 2 with PHP <= 5.6.

#### Silex 1.x
```
"require":{
        "nymo/silex-twig-breadcrumb-extension":"~1.2"
    }
```

Then run composer update.

### Configure Silex
I assume you have already a running Silex application with Twig as a template engine.
First register the breadcrumb service provider:
```
$app->register(new \nymo\Silex\Provider\BreadCrumbServiceProvider());
```
Then register the Twig breadcrumb extension. You have to define this after your registered the Twig service provider
otherwise the application throws an error if you use $app['twig'].

```
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    $twig->addExtension(new \nymo\Twig\Extension\BreadCrumbExtension($app));

    return $twig;
});
```


#### Silex 1.x
```
$app['twig'] = $app->share(
    $app->extend(
        'twig',
        function ($twig, $app) {
            $twig->addExtension(new \nymo\Twig\Extension\BreadCrumbExtension($app));
            return $twig;
        }
    )
);
```

That's all. Now you ready to go.

## Usage
After your successfull installation you can add breadcrumb items wherever you want. All you need is to call the
breadcrumb service and add a item:

### New style
```
$app['breadcrumbs']->addSimpleItem('Silex rocks', 'http://silex.sensiolabs.org/');
$app['breadcrumbs']->addSimpleItem('PHP', 'http://www.php.net');
```
The last item in your container is always printed as plain text without an <a> tag.
You can also add an breadcrumb item without any url. Then this breadcrumb item will also be printed as plain text.

```
$app['breadcrumbs']->addSimpleItem('Just some text');
```
### Old style deprecated

```
$app['breadcrumbs']->addItem('Silex rocks', 'http://silex.sensiolabs.org/');
$app['breadcrumbs']->addItem('PHP', 'http://www.php.net');
```
The last item in your container is always printed as plain text without an <a> tag.
You can also add an breadcrumb item without any url. Then this breadcrumb item will also be printed as plain text.

```
$app['breadcrumbs']->addItem('Just some text');
```

### Named Routes
You can also use named routes. This extension supports two types of named routes a simple and a complex one with parameters.
Before you start to add a named route you will have to register (if not done already) the Silex UrlGeneratorServiceProvider.

```
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
```

Then add this url generator to the breadcrumbs collection.
```
$app['breadcrumbs']->setUrlGenerator($app['url_generator']);
```
Now you're ready to go.

#### Simple named route
If you got a simple route without any required parameters you can add this route to the breadcrumb collection as follows:
### New style
```
$app['breadcrumbs']->addRouteItem('A simple route', ['route' => 'simple_named_route']);
```
### Old style deprecated
```
$app['breadcrumbs']->addItem('A simple route',array('route' => 'simple_named_route'));
```
#### Complex named route
A complex named route is being added the same way as a simple named route. What needs to be done additionally is to pass
an array with the required parameters as a second value to the array.
### New style
```
$app['breadcrumbs']->addRouteItem('A complex route', [
        'route' => 'complex_named_route',
        'params' => [
            'name' => "John",
            'id' => 3
        ]
    ]);
```
### Old style deprecated
```
$app['breadcrumbs']->addItem('A complex route',array(
        'route' => 'complex_named_route',
        'params' => array(
            'name' => "John",
            'id' => 3
        )
    ));
```

#### Rendering breadcrumbs in twig

In your Twig template you can render your breadcrumbs with this function:
```
{{renderBreadCrumbs()}}
```
The default template renders an unordered list. The last item has a css class called lastItem. You can override this
template. Just copy the breadcrumbs.html.twig template from the vendor folder into your view path.

## i18n Support
Since version 1.1.0 this extension supports i18n. Each linkname has an optional translation filter which is
only activated if you use the Translation Service Provider. For further information please refer to the
Silex Documentation.

## Optional configuration
The extension comes with a small configuration option which can be used optional. The default separator used for
for the breadcrumbs is a > sign.
If you want to change it you can pass your own separator when registering the Twig extension:

```
$app['twig']->addExtension(new \nymo\Twig\Extension\BreadCrumbExtension($app),array("breadcrumbs.separator" => "::"));
```
