<?php

namespace spec\Jjanvier\Bundle\CrowdinBundle\Command\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Crowdin\Client as CrowdinClient;
use Crowdin\Api\AddFile;

class FileAddCommandSpec extends ObjectBehavior
{
    function let(InputInterface $input)
    {
        $input->bind(Argument::cetera())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Jjanvier\Bundle\CrowdinBundle\Command\Api\FileAddCommand');
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
        $this->getName()->shouldReturn('crowdin:api:add-file');
    }

    function it_adds_a_file(InputInterface $input, OutputInterface $output, AddFile $api, CrowdinClient $client)
    {
        $input->getArgument('file')->willReturn('messages.yml');
        $input->getArgument('crowdinPath')->willReturn('path/on/crowdin');
        $input->getOption('exportPattern')->willReturn(null);
        $input->getOption('title')->willReturn(null);

        $client->api('add-file')->shouldBeCalled();
        $client->api('add-file')->willReturn($api);
        $this->setClient($client);

        $api->addTranslation('messages.yml', 'path/on/crowdin', null, null)->shouldBeCalled();
        $api->execute()->shouldBeCalled();

        $this->run($input, $output);
    }
}
