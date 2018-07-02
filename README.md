# Norsys Seo Bundle

This project is a bundle to handle SEO meta/link tags configuration & rendering


Installation
============

Step 1: Add the repository & download the Bundle
------------------------------------------------

Open a command console, enter your project directory and execute the
following commands:

```bash
# download the latest stable version of this bundle:
$ composer require norsys/seo-bundle
```

This command requires you to have `composer` installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:


```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Norsys\SeoBundle\NorsysSeoBundle(),
            // ...
        );
        // ...
    }
    // ...
}
```

Step 3: Configuration
----------------------

Next, update your configuration adding the newly installed bundles section

The title/meta/link tags configuration is implemented on a per-route basis.


#### Using the bundle with minimal config

_app/config/config.yml_
```yaml
# ...

# Config options for NorsysSeoBundle
norsys_seo:
    
    # Configuration for titles
    title:
        # This is the fallback value when no title defined for the requested route (MANDATORY)
        default: Acme Demo WebSite

        # Other routes title overrides 
        pages:
            faq: Acme Demo FAQ
            # ...

    # Configuration for meta tags
    metas:
        # This are the fallback values, used for meta-tags when no config exist for the requested route
        defaults:
            - { name: description, content: Acme Demo Website is a website demonstrating the full potential of Acme }

        # Per-route based configuration
        pages:
            # Each route can override and/or implement its own meta-tags
            faq:
                - { name: description, content: Acme's most Frequently Asked Questions, the hottest subjects! }
            # ...

    # Configuration for link tags
    links:
        pages:
            home:
                - { rel: shortcut icon, href: img/favicon.ico, type: image/x-icon }

# ...
```

#### Using the bundle with symfony translation system

Optionally, `<meta>` and `<title>` tags verbatims can be translated using symfony translation system, using keys.

_app/config/config.yml_
```yaml
# ...

# Config options for NorsysSeoBundle
norsys_seo:
    
    # i18n config (if no "translation" key present in config, the default behavior is to have translations disabled)
    translation: 
        # implicitly tell the bundle to use SF translation system
        enabled: true
        # Name of the translation domain to be used for verbatims (MANDATORY)
        domain: seo
    
    # Configuration for titles
    title:
        # This is the fallback value when no title defined for the requested route (MANDATORY)
        default: defaults.title

        # Other routes title overrides 
        pages:
            faq: faq.title
            # ...

    # Configuration for meta tags
    metas:
        # This are the fallback values, used for meta-tags when no config exist for the requested route
        defaults:
            - { charset: UTF-8 }
            - { name: description, content: defaults.description }

        pages:
            # Each route can override and/or implement its own meta-tags
            faq:
                - { name: description, content: faq.description }
            # It's possible to remove the default description with set null (~ in Yaml)
            blog:
                - { name: description, content: ~}

    # Configuration for link tags
    links:
        pages:
            home:
                - { rel: shortcut icon, href: img/favicon.ico, type: image/x-icon }

# ...
```

Translation keys are stored in a proper domain, which name is defined by the value of `domain` under `norsys_seo` config section.

The file as to be named `<domain>`.`<locale>`.`<format>` and reside in `src/AppBundle/Resource/translations/` directory. 
_Format can be one of the following: `xml`,`yml`,`php`,`ini`,`xliff` or any other custom format you implemented (see [Symfony Documentation](http://symfony.com/doc/current/translation.html) for more information)_

_src/AppBundle/Resource/translations/seo.fr.yml_
```yaml
# Translations for SEO meta tags
defaults:
    title:       Acme Demo WebSite
    description: Acme Demo Website is a website demonstrating the full potential of Acme 
faq:
    title:       Acme Demo FAQ
    description: Acme's most Frequently Asked Questions, the hottest subjects!
# ...
```

Step 4: Usage
-------------

This bundle comes up with a `Twig` extension which exposes 3 functions for tag rendering:
- `seo_render_metas(route)`
- `seo_render_links(route)`
- `seo_render_title(route)`

Example:

```twig
{# Render the <title> tag, based on SEO config #}
{{ seo_render_title(app.request.route) }}

{# Render all meta tags #}
{{ seo_render_metas(app.request.route) }}


{# Render all link tags #}
{{ seo_render_links(app.request.route) }}

```

Rewrite
-------

This bundle provide a system to rewrite urls from all requests

To configure rewrite system :

```yaml
norsys_seo:
    rewrite: 
        remove_trailing_slash: true # enable remove trailing slash in urls, default false
```

Sitemap
-------

This bundle can expose `/sitemap.xml` url for you. Just import `routing.yml` in your routing configuration :

```yaml
_norsys_seo:
    resource: "@NorsysSeoBundle/Resources/config/routing.yml"
    prefix:   /
```

Now, you can expose each route to sitemap. Sample :

```yaml
home:
    path: /
    methods: [GET]
    defaults:
        _controller: 'AppBundle\Action\Home'
        template: 'AppBundle::home.html.twig'
    options:
        sitemap: true

```

Sitemap generated:

```xml
<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>/</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
</urlset>
```

## Credits
Developped with :heart: by [Norsys](https://www.norsys.fr/)

## License

This project is licensed under the [MIT license](LICENSE).