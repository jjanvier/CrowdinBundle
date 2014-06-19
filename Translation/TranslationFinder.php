<?php

namespace Jjanvier\Bundle\CrowdinBundle\Translation;

use Symfony\Component\Finder\Finder;

/**
 * Class TranslationFinder
 *
 * Retrieve the translations of your project
 */
class TranslationFinder implements TranslationFinderInterface
{
    /**
     * @var string default locale of the project
     */
    protected $defaultLocale;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $path
     * @param string $defaultLocale
     */
    public function __construct($path, $defaultLocale = 'en')
    {
        $this->path = $path;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @{@inheritdoc}
     */
    public function getTranslations()
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($this->path)
        ;

        return $finder;
    }

    /**
     * @{@inheritdoc}
     */
    public function getDefaultLocaleTranslations()
    {
        $finder = $this->getTranslations();
        $finder->name(sprintf('*.%s.*', $this->defaultLocale));

        return $finder;
    }
}
