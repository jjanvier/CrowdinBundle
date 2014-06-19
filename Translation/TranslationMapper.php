<?php

namespace Jjanvier\Bundle\CrowdinBundle\Translation;

/**
 * Class TranslationMapper
 *
 * Map the translations of your project with the Crowdin translations
 */
class TranslationMapper implements TranslationMapperInterface
{
    /**
     * @{@inheritdoc}
     */
    public function local2crowdin($localPath)
    {
        preg_match('#^.*/(.*Bundle)/.*/(.*\..*\..*)$#', $localPath, $matches);

        return sprintf('%s/%s', $matches[1], $matches[2]);
    }

    /**
     * @{@inheritdoc}
     */
    public function crowdin2local($crowdinPath)
    {
        throw new \LogicException('To be implemented');
    }
} 
