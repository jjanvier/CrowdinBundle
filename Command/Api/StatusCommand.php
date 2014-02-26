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

use Crowdin\Api\Status;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to get the progress of the Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class StatusCommand extends AbstractApiCommand
{
    protected function configure()
    {
        $this
            ->setName('crowdin:api:status')
            ->setDescription('Crowdin project progress by language.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Status $status */
        $status = $this->getClient()->api('status');
        $result = $status->execute();

        $output->writeln($result);
    }
}
