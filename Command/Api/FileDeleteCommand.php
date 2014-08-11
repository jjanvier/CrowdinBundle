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

use Crowdin\Api\DeleteFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to delete a file from the Crowdin project. All the translations will be lost without ability to restore them.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class FileDeleteCommand extends AbstractApiCommand
{
    protected function configure()
    {
        $this
            ->setName('crowdin:api:delete-file')
            ->setDescription('Delete a file from the Crowdin project. All the translations will be lost without ability to restore them.')
            ->addArgument('file', InputArgument::REQUIRED, 'File you want to delete.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DeleteFile $deleteFile */
        $deleteFile = $this->getClient()->api('delete-file');
        $deleteFile->setFile($input->getArgument('file'));

        $result = $deleteFile->execute();

        $output->writeln($result);
    }
}
