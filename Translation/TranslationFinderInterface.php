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
} 
