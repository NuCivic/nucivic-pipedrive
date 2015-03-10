<?php
namespace NuCivicPipedrive\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ExportCommand extends Command {
    protected function configure() {
        $this->setName("export")
             ->setDescription("Export Pipedrive data to CSV")
             ->setDefinition(array(
                new InputArgument('tables', InputArgument::IS_ARRAY, 'Space-separated list of tables to export. Omit for full export', null),
             ))
             ->setHelp(<<<EOT
The <info>export</info> grabs data from Pipedrive and exports to CSV.
EOT
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $tables = $input->getArgument('tables');
        foreach($tables as $table) {
            $output->writeln("Exporting $table...");
        }
    }
}
