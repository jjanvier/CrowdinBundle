<?php

/*
 * This file is part of the Crowdin package.
 *
 * (c) Julien Janvier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jjanvier\Bundle\CrowdinBundle\Command\Crowdin;

use Crowdin\Api\Download;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to download last package from Crowdin.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class DownloadCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('crowdin:download')
            ->setDescription('Download last package from Crowdin.')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Path where you want to download the zip.', '/tmp/crowdin')
            ->addOption('language', 'l', InputOption::VALUE_REQUIRED, 'Language you want to download.', 'all')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getOption('path');
        $file = $input->getOption('language').'.zip';

        if (!is_dir($path) && false === @mkdir($path, 0777, true)) {
            $output->writeln(sprintf('<error>Can not create the directory %s.</error>', $path));

            return self::ERROR_GENERIC;
        }

        if (!is_writable($path)) {
            $output->writeln(sprintf('<error>The directory %s is not writable.</error>', $path));

            return self::ERROR_GENERIC;
        }

        /** @var Download $download */
        $download = $this->getClient()->api('download');
        $download->setCopyDestination($path);
        $download->setPackage($file);
        $download->execute();
    }
}
