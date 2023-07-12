<?php

namespace App\Command;

use App\Service\BlockGeneratorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'wordpress:make:block',
    description: 'Add a short description for your command',
    aliases: ['wp:m:b']
)]
class WordpressMakeBlockCommand extends Command
{
    public function __construct(
        private BlockGeneratorService $blockGenerator,
        string                        $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);

        $qBlockName = new Question("<fg=green>\nPlease enter the name of the new WordPress Block: </>");
        $blockName = $helper->ask($input, $output, $qBlockName);

        $io->writeln('');
        $qBlockTitle = new Question("<fg=green>\nPlease entre the title of the block that will be showed in the Gutenberg Editor: </>");
        $blockTitle = $helper->ask($input, $output, $qBlockTitle);

        $io->writeln('');
        $io->write("<fg=Blue>Ressource: </><fg=yellow>https://developer.wordpress.org/resource/dashicons/#businessman</>");
        $qDashicon = new Question("<fg=green>\nPlease choose a Dashicon: </>");
        $dashicon = $helper->ask($input, $output, $qDashicon);


        $this->blockGenerator->generate($blockName, $blockTitle, $dashicon);

        return Command::SUCCESS;
    }
}
