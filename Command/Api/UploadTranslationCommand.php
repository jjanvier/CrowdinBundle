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

use Crowdin\Api\UploadTranslation;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UploadTranslationCommand extends AbstractApiCommand
{
    protected function configure()
    {
        $this
            ->setName('crowdin:api:upload')
            ->setDescription('Upload latest version of your localization files to Crowdin.')
            ->addArgument('locale', InputArgument::REQUIRED, 'Locale of your translations.')
            ->addArgument('file', InputArgument::REQUIRED, 'Crowdin file you want to upload translations for.')
            ->addArgument('translation', InputArgument::REQUIRED, 'Local translation you want to upload.')
            ->addOption('import-duplicates', null, InputOption::VALUE_NONE, 'Defines whether to add translation if there is the same translation previously added.')
            ->addOption('import-equal-suggestions', null, InputOption::VALUE_NONE, 'Defines whether to add translation if it is equal to source string at Crowdin.')
            ->addOption('auto-approve-imported', null, InputOption::VALUE_NONE, 'Mark uploaded translations as approved.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var UploadTranslation $uploadTranslation */
        $uploadTranslation = $this->getClient()->api('upload-translation');
        $uploadTranslation->setLocale($input->getArgument('locale'));
        $uploadTranslation->addTranslation(
            $input->getArgument('file'),
            $input->getArgument('translation')
        );

        if ($input->getOption('import-duplicates')) {
            $uploadTranslation->setDuplicatesImported(true);
        }

        if ($input->getOption('import-equal-suggestions')) {
            $uploadTranslation->setEqualSuggestionsImported(true);
        }

        if ($input->getOption('auto-approve-imported')) {
            $uploadTranslation->setImportsAutoApproved(true);
        }

        $result = $uploadTranslation->execute();
        $output->writeln($result);
    }
}