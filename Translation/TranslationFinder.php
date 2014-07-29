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
     * @var string path of the project
     */
    protected $path;

    /**
     * @var string pattern to used to find translations
     */
    protected $pattern;

    /**
     * @param string $path
     * @param string $pattern
     * @param string $defaultLocale
     */
    public function __construct($path, $pattern, $defaultLocale = 'en')
    {
        $this->path = $path;
        $this->pattern = $pattern;
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
            ->in($this->path . $this->pattern)
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

    /**
     * @{@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @{@inheritdoc}
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }
}
