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
        $pipedrive = new PipedriveAPI('');
        if ($success = $pipedrive->isAuthenticated()) {
            $output->writeln("Authenticated");

            // Persons
            $output->writeln("Querying pipedrive for persons...");
            $persons = $pipedrive->persons->getAll();
            $personfields = $pipedrive->personfields->getPersonFields();
            $data = array();
            $output->writeln("Translating field names...");
            foreach ($persons as $key => $person) {
                $pipedrive->personfields->translatePersonFieldKeys($person, $personfields);
                $data[$key] = get_object_vars($person);
            }
            $csv = new CSV($data);
            $output->writeln("Cleaning data...");
            $csv->cleanData(array('fields_keep' => $pipedrive->persons->fields_keep));
            $output->writeln("Writing to CSV...");
            $csv->write('persons.csv');
            $output->writeln("Persons exported");

            // Deals
            $deals = $pipedrive->deals->getAll();
            $dealfields = $pipedrive->dealfields->getDealFields();
            $data = array();
            foreach ($deals as $key => $deal) {
                $pipedrive->dealfields->translateDealFieldKeys($deal, $dealfields);
                $data[$key] = get_object_vars($deal);
            }
            $csv = new CSV($data);
            $csv->cleanData(array('fields_keep' => $pipedrive->deals->fields_keep));
            $csv->write('deals.csv');
            $output->writeln("Deals exported");
        }
        else {
            $output->writeln("Not authenticated");
            return;
        }
    }
}
