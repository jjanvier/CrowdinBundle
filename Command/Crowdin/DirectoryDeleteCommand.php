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

use Crowdin\Api\DeleteDirectory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to delete a directory from the Crowdin project. All nested files and directories will be deleted too.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class DirectoryDeleteCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('crowdin:delete-directory')
            ->setDescription('Deletes a Crowdin project directory. All nested files and directories will be deleted too.')
            ->addArgument('directory', InputArgument::REQUIRED, 'Directory path you want to delete.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DeleteDirectory $deleteDirectory */
        $deleteDirectory = $this->getClient()->api('delete-directory');
        $deleteDirectory->setDirectory($input->getArgument('directory'));
        $result = $deleteDirectory->execute();

        $output->writeln($result);
    }
}
