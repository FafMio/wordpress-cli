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
    name: 'wordpress:block:category',
    description: 'Change the default block category while generating new block.',
    aliases: ['wp:b:c']
)]
class WordpressBlockCategoryCommand extends Command
{

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('block_category', InputArgument::OPTIONAL, 'Please enter the slug of the block category.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $block_category = $input->getArgument('block_category');

        if ($block_category) {
            FileService::changeBlockCategory($block_category);
            $io->writeln("");
            $io->writeln(" <fg=blue>→</> Changed default block category slug to: <fg=yellow>" . $block_category . "</>");
            CommandHelperService::writeSuccessMessage($io);

        } else {
            $io->error("You have to specify a path to your WordpressTheme !");
        }

        return Command::SUCCESS;
    }
}
