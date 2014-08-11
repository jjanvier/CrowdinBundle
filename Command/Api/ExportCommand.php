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

use Crowdin\Api\Export;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to a build zip archive with latest Crowdin translations. Can be invoked only once every 30 minutes.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class ExportCommand extends AbstractApiCommand
{
    protected function configure()
    {
        $this
            ->setName('crowdin:api:export')
            ->setDescription('Build a zip archive with latest Crowdin translations. Can be invoked only once every 30 minutes.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Export $export */
        $export = $this->getClient()->api('export');
        $result = $export->execute();

        $output->writeln($result);
    }
}
