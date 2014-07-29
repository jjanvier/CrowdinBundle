<?php

namespace Jjanvier\Bundle\CrowdinBundle\Synchronizer;

use Crowdin\Api\Download;
use Jjanvier\Bundle\CrowdinBundle\Archive\ArchiveInterface;
use Crowdin\Client as CrowdinClient;
use Jjanvier\Bundle\CrowdinBundle\Translation\TranslationFinderInterface;

/**
 * Class DownSynchronizer
 *
 * Update your project with latest Crowdin translations and open a PR on Github.
 */
class DownSynchronizer implements SynchronizerInterface
{
    const CROWDIN_PACKAGE = 'all.zip';

    /** @var string */
    protected $id;

    /**
     * @var CrowdinClient
     */
    protected $crowdinClient;

    /**
     * @var ArchiveInterface
     */
    protected $archive;

    /**
     * @var TranslationFinderInterface
     */
    protected $translationsFinder;

    /**
     * @var GitHandler
     */
    protected $gitHandler;

    /**
     * @param TranslationFinderInterface    $finder
     * @param ArchiveInterface              $archive
     * @param GitHandler                    $gitHandler
     * @param CrowdinClient                 $crowdinClient
     */
    public function __construct(
        TranslationFinderInterface $finder,
        ArchiveInterface $archive,
        GitHandler $gitHandler,
        CrowdinClient $crowdinClient
    ) {
        $this->translationsFinder = $finder;
        $this->archive = $archive;
        $this->gitHandler = $gitHandler;
        $this->crowdinClient = $crowdinClient;
        $this->id = uniqid();
    }

    /**
     * @{@inheritdoc}
     */
    public function synchronize()
    {
        // init project
        $projectDir = $this->createProjectDirectory();
        $this->translationsFinder->setPath($projectDir);
        $this->gitHandler->setProjectPath($projectDir);
        $this->gitHandler->cloneProject();

        // download and extract Crowdin translations
        $this->downloadPackage();
        $this->extractPackage($projectDir);

        // create new local branch
        $branch = $this->gitHandler->getBranchName();
        $this->gitHandler->createBranch($branch);

        // commit and push
        $this->resetDefaultLocaleTranslations();
        $this->gitHandler->commit();
        $this->gitHandler->pushBranch($branch);

        // open PR on Github
        $this->gitHandler->createPullRequest($branch);
    }

    /**
     * Download a fresh Crowdin package
     *
     * @throws \Exception
     */
    protected function downloadPackage()
    {
        $this->crowdinClient->api('export')->execute();
        $path = $this->createArchiveDirectory();

        /** @var Download $download */
        $download = $this->crowdinClient->api('download');
        $download->setCopyDestination($path);
        $download->setPackage(self::CROWDIN_PACKAGE);
        $download->execute();

        $this->archive->setFilename(self::CROWDIN_PACKAGE);
        $this->archive->setPath($path);
    }

    /**
     * Extract the Crowdin package
     *
     * @param $projectPath
     */
    protected function extractPackage($projectPath)
    {
        $this->archive->setExtractPath($projectPath);
        $this->archive->extract()->remove();
    }

    /**
     * Undo changes possibly made to the default locale translations
     */
    protected function resetDefaultLocaleTranslations()
    {
        $translations = $this->translationsFinder->getDefaultLocaleTranslations();
        foreach ($translations as $translation) {
            $this->gitHandler->reset($translation->getPathname());
        }
    }

    /**
     * Create the project directory
     * TODO: use the config parameter
     *
     * @return string
     */
    protected function createProjectDirectory()
    {
        $dir = sprintf('/tmp/crowdin/project/%s/', $this->id);
        exec(sprintf('mkdir -p %s', $dir));

        return $dir;
    }

    /**
     * Create the archive directory
     * TODO: use the config parameter
     *
     * @return string
     */
    protected function createArchiveDirectory()
    {
        $dir = sprintf('/tmp/crowdin/archive/%s/', $this->id);
        exec(sprintf('mkdir -p %s', $dir));

        return $dir;
    }
}
