<?php

namespace Jjanvier\Bundle\CrowdinBundle\Translation;

/**
 * Interface TranslationMapperInterface
 *
 * Map the translations of your project with the Crowdin translations
 */
interface TranslationMapperInterface
{
    /**
     * Return the Crowdin path from the local path
     *
     * @param $localPath
     *
     * @return string
     */
    public function local2crowdin($localPath);

    /**
     * Return the local path from the Crowdin path
     *
     * @param $crowdinPath
     *
     * @return string
     */
    public function crowdin2local($crowdinPath);
}
