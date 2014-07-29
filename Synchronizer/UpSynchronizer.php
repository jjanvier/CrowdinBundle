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
     * @var GitHandler
     */
    protected $gitHandler;

    /**
     * @param CrowdinClient     $client
     * @param GitHandler        $gitHandler
     * @param TranslationFinder $finder
     * @param TranslationMapper $mapper
     */
    public function __construct(
        CrowdinClient $client,
        GitHandler $gitHandler,
        TranslationFinder $finder,
        TranslationMapper $mapper
    ) {
        $this->crowdinClient = $client;
        $this->gitHandler = $gitHandler;
        $this->finder = $finder;
        $this->mapper = $mapper;
    }

    /**
     * {@inheritdoc}
     */
    public function synchronize()
    {
        // init project
        $projectDir = $this->createProjectDirectory();
        $this->finder->setPath($projectDir);
        $this->gitHandler->setProjectPath($projectDir);
        $this->gitHandler->cloneProject();

        /** @var UpdateFile $api */
        $api = $this->crowdinClient->api('update-file');

        foreach ($this->finder->getDefaultLocaleTranslations() as $localPath) {
            $crowdinPath = $this->mapper->local2crowdin($localPath);
            $api->addTranslation($localPath, $crowdinPath);
        }

        return $api->execute();
    }

    /**
     * Create the project directory
     * TODO: use the config parameter
     *
     * @return string
     */
    protected function createProjectDirectory()
    {
        $dir = sprintf('/tmp/crowdin/project/%s/', uniqid());
        exec(sprintf('mkdir -p %s', $dir));

        return $dir;
    }
}
