<?php

namespace spec\Jjanvier\Bundle\CrowdinBundle\Command\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Crowdin\Client as CrowdinClient;
use Crowdin\Api\UploadTranslation;

class UploadTranslationCommandSpec extends ObjectBehavior
{
    function let(InputInterface $input)
    {
        $input->bind(Argument::cetera())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Jjanvier\Bundle\CrowdinBundle\Command\Api\UploadTranslationCommand');
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
        $this->getName()->shouldReturn('crowdin:api:upload');
    }

    function it_deletes_a_file(InputInterface $input, OutputInterface $output, UploadTranslation $api, CrowdinClient $client)
    {
        $input->getArgument('locale')->willReturn('fr');
        $input->getArgument('file')->willReturn('/path/on/crowdin/messages.yml');
        $input->getArgument('translation')->willReturn('/locale/path/of/my/translation.fr.yml');
        $input->getOption('import-duplicates')->willReturn(true);
        $input->getOption('import-equal-suggestions')->willReturn(true);
        $input->getOption('auto-approve-imported')->willReturn(true);

        $client->api('upload-translation')->shouldBeCalled();
        $client->api('upload-translation')->willReturn($api);
        $this->setClient($client);

        $api->setLocale('fr')->shouldBeCalled();
        $api->addTranslation('/path/on/crowdin/messages.yml', '/locale/path/of/my/translation.fr.yml')->shouldBeCalled();
        $api->setDuplicatesImported(true)->shouldBeCalled();
        $api->setEqualSuggestionsImported(true)->shouldBeCalled();
        $api->setImportsAutoApproved(true)->shouldBeCalled();
        $api->execute()->shouldBeCalled();

        $this->run($input, $output);
    }


}
