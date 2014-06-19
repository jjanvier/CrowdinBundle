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

use Jjanvier\Bundle\CrowdinBundle\Synchronizer\UpSynchronizer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Send translations of the default locale to Crowdin
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class UpSyncCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('crowdin:sync:up')
            ->setDescription('Send translations of the default locale to Crowdin.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getSynchronizerService()->synchronize();

        return 0;
    }

    /**
     * @return UpSynchronizer
     */
    protected function getSynchronizerService()
    {
        return $this->getContainer()->get('jjanvier_crowdin.synchronizer.up_synchronizer');
    }
}
