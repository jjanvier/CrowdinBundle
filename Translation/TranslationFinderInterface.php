<?php

namespace Jjanvier\Bundle\CrowdinBundle\Translation;

use Symfony\Component\Finder\Finder;

/**
 * Interface TranslationFinderInterface
 *
 * Retrieve the translations of your project
 */
interface TranslationFinderInterface
{
    /**
     * Get translations to send to Crowdin
     *
     * @return Finder|\SplFileInfo[]
     */
    public function getTranslations();

    /**
     * Get translations in the default locale of your project to send to Crowdin
     *
     * @return Finder|\SplFileInfo[]
     */
    public function getDefaultLocaleTranslations();

    /**
     * Set the path where to search for the translations
     *
     * @param string $path
     *
     * @return TranslationFinderInterface
     */
    public function setPath($path);

    /**
     * Set the pattern used to find translations
     *
     * @param string $pattern
     *
     * @return TranslationFinderInterface
     */
    public function setPattern($pattern);
}
