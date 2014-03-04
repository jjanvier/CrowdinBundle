<?php

namespace spec\Jjanvier\Bundle\CrowdinBundle\Command\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Crowdin\Client as CrowdinClient;
use Crowdin\Api\Export;

class ExportCommandSpec extends ObjectBehavior
{
    function let(InputInterface $input)
    {
        $input->bind(Argument::cetera())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Jjanvier\Bundle\CrowdinBundle\Command\Api\ExportCommand');
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
        $this->getName()->shouldReturn('crowdin:api:export');
    }

    function it_export_the_package(InputInterface $input, OutputInterface $output, Export $api, CrowdinClient $client)
    {
        $client->api('export')->shouldBeCalled();
        $client->api('export')->willReturn($api);
        $this->setClient($client);

        $api->execute()->shouldBeCalled();

        $this->run($input, $output);
    }
}
