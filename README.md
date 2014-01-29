CrowdinBundle
=============

Manage your Crowdin translations via Symfony2 commands.

Installation
------------

We assume you're familiar with [Composer](http://packagist.org), a dependency manager for PHP.
Use following command to add the bundle to your `composer.json` and download package.

If you have [Composer installed globally](http://getcomposer.org/doc/00-intro.md#globally).

```bash
    $ composer require jjanvier/crowdin-bundle:*@dev
```

Otherwise you have to download .phar file.

```bash
    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar require janvier/crowdin-bundle:*@dev
```

Adding required bundles to the kernel
-------------------------------------

You need to enable the bundle inside the symfony kernel.

```php
    <?php

    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            new Jjanvier\Bundle\CrowdinBundle\JjanvierCrowdinBundle(),
        );
    }
```

Configuration
-------------

Add the following configuration to your `config.yml` :

```yaml
    jjanvier_crowdin:
        crowdin_api_key: %crowdin_api_key%
        crowdin_project_identifier: %crowdin_project_identifier%
```

Add the following keys to your `parameters.yml`:

```yaml
    crowdin_api_key: MY_API_KEY
    crowdin_project_identifier: MY_PROJECT_IDENTIFIER
```
