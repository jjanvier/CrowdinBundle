<?php

/*
 * This file is part of the Crowdin package.
 *
 * (c) Julien Janvier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jjanvier\Bundle\CrowdinBundle\Command\Translation;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to download last package from Crowdin.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class ExtractCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('translations:crowdin:extract')
            ->setDescription('Retrieve translations of your project and extract them.')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Path where you want to extract your translations.', '/tmp/crowdin')
            ->addOption('language', 'l', InputOption::VALUE_REQUIRED, 'Language you want to extract.', 'all')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getOption('path');
        $file = $input->getOption('language').'.zip';

        if (0 !== $returnCode = $this->exportFromCrowdin($input, $output)) {
            return $returnCode;
        }

        if (0 !== $returnCode = $this->downloadFromCrowdin($input, $output)) {
            return $returnCode;
        }

        $zip = new \ZipArchive();
        if (true !== $zip->open($path.'/'.$file)) {
            $output->writeln(sprintf('<error>The zip file %s/%s can not be opened.</error>', $path, $file));
        }

        $zip->extractTo($path);
        $zip->close();

        unlink($path.'/'.$file);
    }

    private function exportFromCrowdin(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('crowdin:export');
        $arguments = array(
            'command' => 'crowdin:export'
        );
        $input = new ArrayInput($arguments);

        return $command->run($input, $output);
    }

    private function downloadFromCrowdin(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('crowdin:download');
        $arguments = array(
            'command' => 'crowdin:download',
            '--path' => $input->getOption('path'),
            '--language' => $input->getOption('language'),
        );
        $input = new ArrayInput($arguments);

        return $command->run($input, $output);
    }
}
