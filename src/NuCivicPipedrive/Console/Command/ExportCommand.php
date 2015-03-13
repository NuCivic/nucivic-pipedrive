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
        $pipedrive = new PipedriveAPI();
        if ($success = $pipedrive->isAuthenticated()) {
            $output->writeln("Authenticated");

            // People
            // $output->writeln("Querying pipedrive for persons...");
            // $data = $pipedrive->persons->getAll();
            // $output->writeln("Cleaning up person data...");
            // $pipedrive->persons->cleanData($data);
            // $csv = new CSV($data);
            // $csv->write('persons.csv');
            // $output->writeln("Persons exported");

            // // Organizations
            // $output->writeln("Querying pipedrive for organizations...");
            // $data = $pipedrive->organizations->getAll();
            // $output->writeln("Cleaning up orgnaizations data...");
            // $pipedrive->organizations->cleanData($data);
            // $csv = new CSV($data);
            // $csv->write('organizations.csv');
            // $output->writeln("Organizations exported");

            // // Deals
            // $output->writeln("Querying pipedrive for deals...");
            // $data = $pipedrive->deals->getAll();
            // $output->writeln("Cleaning up deal data...");
            // $pipedrive->deals->cleanData($data);
            // $csv = new CSV($data);
            // $csv->write('deals.csv');
            // $output->writeln("<info>Deals exported</info>");

            // // Products
            // $output->writeln("Querying pipedrive for products...");
            // $data = $pipedrive->products->getAll();
            // $output->writeln("Cleaning up product data...");
            // $pipedrive->products->cleanData($data);
            // $csv = new CSV($data);
            // $csv->write('products.csv');
            // $output->writeln("<info>Products exported</info>");

            // Activities
            // $output->writeln("Querying pipedrive for activities...");
            // $data = $pipedrive->activities->getAll();
            // $output->writeln("Cleaning up product data...");
            // $pipedrive->activities->cleanData($data);
            // $csv = new CSV($data);
            // $csv->write('activities.csv');
            // $output->writeln("<info>Activities exported</info>");

            // Files
            // $output->writeln("Querying pipedrive for files...");
            // $data = $pipedrive->files->getAll();
            // $output->writeln("Cleaning up file data...");
            // $pipedrive->files->cleanData($data);
            // $csv = new CSV($data);
            // $csv->write('files.csv');
            // $output->writeln("Downloading files to disk...");
            // $pipedrive->files->downloadFiles($data);
            // $output->writeln("<info>Files exported</info>");

            // Notes
            $output->writeln("Querying pipedrive for notes...");
            $data = $pipedrive->notes->getAll();
            $output->writeln("Cleaning up note data...");
            $pipedrive->notes->cleanData($data);
            $csv = new CSV($data);
            $csv->write('notes.csv');
            $output->writeln("<info>Notes exported</info>");
        }
        else {
            $output->writeln("Not authenticated");
            return;
        }
    }
}
