<?php

namespace spec\Jjanvier\Bundle\CrowdinBundle\Command\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Crowdin\Client as CrowdinClient;
use Crowdin\Api\AddDirectory;

class DirectoryAddCommandSpec extends ObjectBehavior
{
    function let(InputInterface $input)
    {
        $input->bind(Argument::cetera())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Jjanvier\Bundle\CrowdinBundle\Command\Api\DirectoryAddCommand');
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
        $this->getName()->shouldReturn('crowdin:api:add-directory');
    }

    function it_adds_a_directory_recursively(InputInterface $input, OutputInterface $output, AddDirectory $api, CrowdinClient $client)
    {
        $input->getArgument('directory')->willReturn('directory/to/add');

        $client->api('add-directory')->shouldBeCalled();
        $client->api('add-directory')->willReturn($api);
        $this->setClient($client);

        $api->setDirectory('directory/')->shouldBeCalled();
        $api->setDirectory('directory/to/')->shouldBeCalled();
        $api->setDirectory('directory/to/add/')->shouldBeCalled();
        $api->execute()->shouldBeCalled();

        $this->run($input, $output);
    }
}
