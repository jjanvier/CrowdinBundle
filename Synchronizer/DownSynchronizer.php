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
    }

    /**
     * @{@inheritdoc}
     */
    public function synchronize()
    {
        // download Crowdin translations
        $this->downloadPackage();

        // create new local branch
        $branch = $this->gitHandler->getBranchName();
        $this->gitHandler->pullMaster();
        $this->gitHandler->createBranch($branch);

        // extract Crowdin package
        $this->extractPackage();

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
        $path = $this->archive->getPath();

        if (!is_dir($path) && false === @mkdir($path, 0777, true)) {
            throw new \Exception(sprintf('Can not create the directory %s.', $path));
        }

        if (!is_writable($path)) {
            throw new \Exception(sprintf('The directory %s is not writable.', $path));
        }

        /** @var Download $download */
        $download = $this->crowdinClient->api('download');
        $download->setCopyDestination($path);
        $download->setPackage(self::CROWDIN_PACKAGE);
        $download->execute();

        $this->archive->setFilename(self::CROWDIN_PACKAGE);
    }

    /**
     * Extract the Crowdin package
     */
    protected function extractPackage()
    {
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
}
