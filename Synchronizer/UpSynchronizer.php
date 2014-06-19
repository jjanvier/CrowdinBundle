<?php

namespace Jjanvier\Bundle\CrowdinBundle\Synchronizer;

use Crowdin\Api\UpdateFile;
use Jjanvier\Bundle\CrowdinBundle\Translation\TranslationFinder;
use Jjanvier\Bundle\CrowdinBundle\Translation\TranslationMapper;
use Crowdin\Client as CrowdinClient;

/**
 * Class UpSynchronizer
 *
 * Send translations of the default locale to Crowdin
 */
class UpSynchronizer implements SynchronizerInterface
{
    /**
     * @var CrowdinClient
     */
    protected $crowdinClient;

    /**
     * @var TranslationFinder
     */
    protected $finder;

    /**
     * @var TranslationMapper
     */
    protected $mapper;

    /**
     * @param CrowdinClient     $client
     * @param TranslationFinder $finder
     * @param TranslationMapper $mapper
     */
    public function __construct(CrowdinClient $client, TranslationFinder $finder, TranslationMapper $mapper)
    {
        $this->crowdinClient = $client;
        $this->finder = $finder;
        $this->mapper = $mapper;
    }

    /**
     * {@inheritdoc}
     */
    public function synchronize()
    {
        /** @var UpdateFile $api */
        $api = $this->crowdinClient->api('update-file');

        foreach ($this->finder->getDefaultLocaleTranslations() as $localPath) {
            $crowdinPath = $this->mapper->local2crowdin($localPath);
            $api->addTranslation($localPath, $crowdinPath);
        }

        return $api->execute();
    }
}
