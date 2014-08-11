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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Crowdin\Client;

/**
 * Abstract Crowdin command.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
abstract class AbstractApiCommand extends ContainerAwareCommand
{
    const ERROR_GENERIC = -1;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @return Client
     */
    public function getClient()
    {
        if (null == $this->client) {
            $this->client = new Client(
                $this->getContainer()->getParameter('crowdin_project_identifier'),
                $this->getContainer()->getParameter('crowdin_api_key')
            );
        }

        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }
}
