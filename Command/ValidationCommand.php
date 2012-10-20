<?php

namespace Zeroem\JsonSchemaValidatorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\HttpFoundation\Request;
use JsonSchema\Validator;

class ValidationCommand extends ContainerAwareCommand
{
    const DEFAULT_URL = 'http://tools.4over.com/cgi-bin/plotcap/export_all_plot?outputstyle=plain';

    protected function configure() {
        $this
            ->setName('json:validate')
            ->setDescription('Validate a JSON blob against a schema')
            ->addOption('print', null, InputArgument::VALUE_NONE, 'Echo printable response')
            ->addArgument('schema', InputArgument::REQUIRED, 'Schema to validate against')
            ->addArgument('json', InputArgument::REQUIRED, 'JSON to validate');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $validator = new Validator;

        $json = $this->getContainer()->get('json');

        $schema = $json->decode(file_get_contents($input->getArgument('schema')));
        $json = $json->decode(file_get_contents($input->getArgument('json')));

        $validator->check($json, $schema);

        if($this->getOption('print')) {
            if($validator->isValid()) {
                $output->writeln('VALID');
            } else {
                $output->writeln('INVALID');
            }
        }

        if($validator->isValid()) {
            return 0;
        } else {
            return 1;
        }
    } 
}

