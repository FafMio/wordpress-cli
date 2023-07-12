<?php

namespace App\Service;

use Symfony\Component\Console\Style\SymfonyStyle;

class CommandHelperService
{
    public static function writeSuccessMessage(SymfonyStyle $io): void
    {
        $io->newLine();
        $io->writeln(' <bg=green;fg=white>          </>');
        $io->writeln(' <bg=green;fg=white> Success! </>');
        $io->writeln(' <bg=green;fg=white>          </>');
        $io->newLine();
    }
}