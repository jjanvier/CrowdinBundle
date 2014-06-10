<?php

/*
 * This file is part of the Crowdin package.
 *
 * (c) Julien Janvier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jjanvier\Bundle\CrowdinBundle\Command;

use Jjanvier\Bundle\CrowdinBundle\Archive\Archive;
use Jjanvier\Bundle\CrowdinBundle\Archive\ArchiveInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to retrieve the latest package of translations from Crowdin and extract it.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class ExtractCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('crowdin:extract')
            ->setDescription('Retrieve translations of your project and extract them.')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Path where you want to extract your translations.', '/tmp/crowdin')
            ->addOption('language', 'l', InputOption::VALUE_REQUIRED, 'Language you want to extract.', 'all')
            ->addOption('header', 't', InputOption::VALUE_REQUIRED, 'Custom header you want to add to your translations.')
            ->addOption('clean', 'c', InputOption::VALUE_NONE, 'If you want to clean Crowdin files.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (0 !== $returnCode = $this->exportFromCrowdin($input, $output)) {
            return $returnCode;
        }

        if (0 !== $returnCode = $this->downloadFromCrowdin($input, $output)) {
            return $returnCode;
        }

        $destination = $input->getOption('path');
        $file = $input->getOption('language').'.zip';
        $archivePath = sprintf('%s/%s', $destination, $file);

        $archive = new Archive(
            $archivePath,
            $destination,
            $input->getOption('clean'),
            $input->getOption('header')
        );
        $archive->extract()->remove();

        return 0;
    }

    /**
     * Generate translations package via the command crowdin:api:export
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int status of the command
     */
    protected function exportFromCrowdin(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('crowdin:api:export');
        $arguments = array(
            'command' => 'crowdin:api:export'
        );
        $input = new ArrayInput($arguments);

        return $command->run($input, $output);
    }

    /**
     * Download the translations package via the command crowdin:api:download
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int status of the command
     */
    protected function downloadFromCrowdin(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('crowdin:api:download');
        $arguments = array(
            'command' => 'crowdin:api:download',
            '--path' => $input->getOption('path'),
            '--language' => $input->getOption('language'),
        );
        $input = new ArrayInput($arguments);

        return $command->run($input, $output);
    }
}
