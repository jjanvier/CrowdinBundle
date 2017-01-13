CrowdinBundle
=============

Manage your Crowdin translations via Symfony2 commands thanks to the [akeneo/crowdin-api](https://github.com/akeneo/php-crowdin-api) library.

This package is not maintained anymore!
---------------------------------------

Please use [akeneo/nelson](https://github.com/akeneo/nelson) instead, which is up-to-date, maintained and contains more features.

Installation
------------

We assume you're familiar with [Composer](http://packagist.org), a dependency manager for PHP.
Use following command to add the bundle to your `composer.json` and download package.

If you have [Composer installed globally](http://getcomposer.org/doc/00-intro.md#globally).

```bash
    $ composer require "jjanvier/crowdin-bundle":"*@dev"
```

Otherwise you have to download .phar file.

```bash
    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar require "jjanvier/crowdin-bundle":"*@dev"
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
        crowdin:
            api_key: %crowdin_api_key%
            project_identifier: %crowdin_project_identifier%
```

Add the following keys to your `parameters.yml`:

```yaml
    crowdin_api_key: MY_API_KEY
    crowdin_project_identifier: MY_PROJECT_IDENTIFIER
```

Existing commands
-----------------

* `crowdin:api:add-directory` adds a directory to the Crowdin project.
* `crowdin:api:delete-directory` deletes a Crowdin project directory. All nested files and directories will be deleted too.
* `crowdin:api:download` downloads last package from Crowdin.
* `crowdin:api:export` builds a zip archive with latest Crowdin translations. Can be invoked only once every 30 minutes.
* `crowdin:api:add-file` adds a new file to the Crowdin project.
* `crowdin:api:update-file` updates an existing file in the Crowdin project.
* `crowdin:api:delete-file` deletes a file from the Crowdin project. All the translations will be lost without ability to restore them.
* `crowdin:api:status` gets Crowdin project progress by language.
* `crowdin:api:upload` uploads latest version of your localization files to Crowdin.
* `crowdin:extract` retrieves translations of your project and extract them.



