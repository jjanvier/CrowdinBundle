<?php

namespace spec\Jjanvier\Bundle\CrowdinBundle\Command\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Crowdin\Client as CrowdinClient;
use Crowdin\Api\DeleteFile;

class FileDeleteCommandSpec extends ObjectBehavior
{
    function let(InputInterface $input)
    {
        $input->bind(Argument::cetera())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Jjanvier\Bundle\CrowdinBundle\Command\Api\FileDeleteCommand');
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
        $this->getName()->shouldReturn('crowdin:api:delete-file');
    }

    function it_deletes_a_file(InputInterface $input, OutputInterface $output, DeleteFile $api, CrowdinClient $client)
    {
        $input->getArgument('file')->willReturn('/path/on/crowdin/messages.yml');

        $client->api('delete-file')->shouldBeCalled();
        $client->api('delete-file')->willReturn($api);
        $this->setClient($client);

        $api->setFile('/path/on/crowdin/messages.yml')->shouldBeCalled();
        $api->execute()->shouldBeCalled();

        $this->run($input, $output);
    }
}
