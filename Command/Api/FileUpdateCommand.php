<?php

/*
 * This file is part of the Crowdin package.
 *
 * (c) Julien Janvier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jjanvier\Bundle\CrowdinBundle\Command\Api;

use Crowdin\Api\UpdateFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FileUpdateCommand extends AbstractApiCommand
{
    protected function configure()
    {
        $this
            ->setName('crowdin:api:update-file')
            ->setDescription('Update a file in the Crowdin project.')
            ->addArgument('file', InputArgument::REQUIRED, 'File you want to update.')
            ->addArgument('crowdinPath', InputArgument::REQUIRED, 'Crowdin path for file you want to update.')
            ->addOption('exportPattern', 'p', InputOption::VALUE_REQUIRED, 'Export pattern of your file.')
            ->addOption('title', 't', InputOption::VALUE_REQUIRED, 'Title of your file.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var UpdateFile $updateFile */
        $updateFile = $this->getClient()->api('update-file');
        $updateFile->addTranslation(
            $input->getArgument('file'),
            $input->getArgument('crowdinPath'),
            $input->getOption('exportPattern'),
            $input->getOption('title')
        );

        $output->writeln($updateFile->execute());
    }
}
