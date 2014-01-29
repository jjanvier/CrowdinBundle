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

use Crowdin\Api\AddDirectory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to add a directory to the Crowdin project. All nested directories will be created too.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class DirectoryAddCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('crowdin:add-directory')
            ->setDescription('Add a directory to the Crowdin project.')
            ->addArgument('directory', InputArgument::REQUIRED, 'Directory path you want to add.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = '';

        foreach (explode('/', $input->getArgument('directory')) as $directory) {
            $path .= $directory.'/';

            /** @var AddDirectory $addDirectory */
            $addDirectory = $this->getClient()->api('add-directory');
            $addDirectory->setDirectory($path);
            $result = $addDirectory->execute();

            $output->writeln(sprintf('Creating directory %s', $path));
            $output->writeln($result);
        }
    }
}
