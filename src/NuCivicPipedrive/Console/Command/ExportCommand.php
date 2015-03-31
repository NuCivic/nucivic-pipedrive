<?php
namespace NuCivicPipedrive\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use NuCivicPipedrive\Pipedrive\Pipedrive as PipedriveAPI;
use NuCivicPipedrive\Pipedrive\Library\CSV;


class ExportCommand extends Command {

    protected $dealData;
    protected $pipedrive;

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
        $this->pipedrive = new PipedriveAPI();
        if ($success = $this->pipedrive->isAuthenticated()) {
            $output->writeln("Authenticated");

            $tables = $input->getArgument('tables');
            if(empty($tables)) {
                $tables = array(
                    'deals',
                    'dealParticipants',
                    'persons',
                    'dealProducts',
                    'products',
                    'organizations',
                    'activities',
                    'files',
                    'notes',
                    'users',
                );
            }

            // Deals - we need the deals data for sub-deal objects too so load
            // regardless

            if (in_array('deals', $tables)
                || in_array('dealParticipants', $tables)
                || in_array('dealProducts', $tables))
            {
                $output->writeln("Querying pipedrive for deals...");
                $this->dealData = $this->pipedrive->deals->getAll();

            }
            foreach($tables as $table) {
                $data = $this->exportTable($table, $output);
                if ($table == 'files') {
                    $output->writeln("Downloading files to disk...");
                    $this->pipedrive->files->downloadFiles($data);
                    $output->writeln("<info>Files exported</info>");
                }
            }

        }
        else {
            $output->writeln("Not authenticated");
            return;
        }
    }

    protected function exportTable($table, $output) {
        $output->writeln("Querying pipedrive for {$table}...");
        $data = $this->pipedrive->$table->getAll($this->dealData);
        $output->writeln("Cleaning up $table data...");
        $this->pipedrive->$table->cleanData($data);
        $csv = new CSV($data);
        $csv->write("{$table}.csv");
        $output->writeln("<info>$table exported</info>");
        return $data;
    }
}
