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

use Jjanvier\Bundle\CrowdinBundle\Synchronizer\DownSynchronizer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Update your project with latest Crowdin translations and open a PR on Github
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class DownSyncCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('crowdin:sync:down')
            ->setDescription('Update your project with latest Crowdin translations and open a PR on Github.')
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
     * @return DownSynchronizer
     */
    protected function getSynchronizerService()
    {
        return $this->getContainer()->get('jjanvier_crowdin.synchronizer.down_synchronizer');
    }
}
