<?php

namespace spec\Jjanvier\Bundle\CrowdinBundle\Command\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Crowdin\Api\Download;
use Crowdin\Client as CrowdinClient;

class DownloadCommandSpec extends ObjectBehavior
{

    function let(InputInterface $input)
    {
        $input->bind(Argument::cetera())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Jjanvier\Bundle\CrowdinBundle\Command\Api\DownloadCommand');
    }

    function it_is_an_abstract_command()
    {
        $this->shouldHaveType('Jjanvier\Bundle\CrowdinBundle\Command\Api\AbstractApiCommand');
    }

    function it_is_a_container_aware_command()
    {
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand');
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('crowdin:api:download');
    }

    function it_downloads_the_package(InputInterface $input, OutputInterface $output, Download $api, CrowdinClient $client)
    {
        $input->getOption('path')->willReturn('/tmp/crowdin');
        $input->getOption('language')->willReturn('all');

        $client->api('download')->shouldBeCalled();
        $client->api('download')->willReturn($api);
        $this->setClient($client);

        $api->setCopyDestination('/tmp/crowdin')->shouldBeCalled();
        $api->setPackage('all.zip')->shouldBeCalled();
        $api->execute()->shouldBeCalled();

        $this->run($input, $output);
    }


}
