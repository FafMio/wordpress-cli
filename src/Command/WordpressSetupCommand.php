<?php

namespace App\Command;

use App\Service\CommandHelperService;
use App\Service\FileService;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'wordpress:setup',
    description: 'Add a short description for your command',
    aliases: ['wp:s']
)]
class WordpressSetupCommand extends Command
{

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('wp_dir', InputArgument::OPTIONAL, 'Path to the WordPress Theme.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $wp_dir = $input->getArgument('wp_dir');

        if ($wp_dir) {
            FileService::setup($wp_dir);
            $io->writeln("");
            $io->writeln(" <fg=blue>→</> New path: <fg=yellow>" . $wp_dir . "</>");
            CommandHelperService::writeSuccessMessage($io);

        } else {
            $io->error("You have to specify a path to your WordpressTheme !");
        }

        return Command::SUCCESS;
    }
}
