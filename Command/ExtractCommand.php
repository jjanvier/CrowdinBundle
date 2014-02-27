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

use Jjanvier\Bundle\CrowdinBundle\Extractor\Extractor;
use Jjanvier\Bundle\CrowdinBundle\Formatter\FormatterFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to retrieve the lastest package of translations from Crowdin and extract it.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class ExtractCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('crowdin:extract')
            ->setDescription('Retrieve translations of your project and extract them.')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Path where you want to extract your translations.', '/tmp/crowdin')
            ->addOption('language', 'l', InputOption::VALUE_REQUIRED, 'Language you want to extract.', 'all')
            ->addOption('header', 'h', InputOption::VALUE_REQUIRED, 'Custom header you want to add to your translations.')
            ->addOption('clean', 'c', InputOption::VALUE_NONE, 'If you want to clean Crowdin files.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (0 !== $returnCode = $this->exportFromCrowdin($input, $output)) {
            return $returnCode;
        }

        if (0 !== $returnCode = $this->downloadFromCrowdin($input, $output)) {
            return $returnCode;
        }

        $path = $input->getOption('path');
        $file = $input->getOption('language').'.zip';
        $archive = sprintf('%s/%s', $path, $file);

        $extractor = new Extractor();
        $translations = $extractor->extract($archive)->getFiles();
        unlink($archive);

        $clean = $input->getOption('clean');
        $header = $input->getOption('header');

        if (false === $clean && null === $header) {
           return 0;
        }

        foreach ($translations as $translation) {
            $formatter = FormatterFactory::getInstance($translation);

            if ($clean) {
                $formatter->clean($translation);
            }

            if ($header) {
                $formatter->addHeader($translation, $header);
            }
        }
    }

    private function exportFromCrowdin(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('crowdin:export');
        $arguments = array(
            'command' => 'crowdin:api:export'
        );
        $input = new ArrayInput($arguments);

        return $command->run($input, $output);
    }

    private function downloadFromCrowdin(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('crowdin:download');
        $arguments = array(
            'command' => 'crowdin:api:download',
            '--path' => $input->getOption('path'),
            '--language' => $input->getOption('language'),
        );
        $input = new ArrayInput($arguments);

        return $command->run($input, $output);
    }
}
