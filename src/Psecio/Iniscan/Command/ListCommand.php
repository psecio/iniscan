<?php
namespace Psecio\Iniscan\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ListCommand extends Command
{
    protected function configure()
    {
        $this->setName('list')
            ->setDescription('Output information about the current rule checks')
            ->setDefinition(array(
                new InputOption('format', 'format', InputOption::VALUE_OPTIONAL, 'Output format'),
            ))
            ->setHelp(
                'Output information about the current rule checks'
            );
    }

    /**
     * Execute the "list" command
     *
     * @param  InputInterface  $input  Input object
     * @param  OutputInterface $output Output object
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $format = $input->getOption('format');
        $scan = new \Psecio\Iniscan\Scan();
        $rules = $scan->getRules();
        $options = array();

        $format = ($format === null) ? 'console' : $format;
        $formatClass = "\\Psecio\\Iniscan\\Command\\ListCommand\\Output\\".ucwords(strtolower($format));
        if (!class_exists($formatClass)) {
            throw new \Psecio\Iniscan\Exceptions\FormatNotFoundException('Output format "'.$format.'" not found');
        }
        $outputHandler = new $formatClass($output, $options);
        return $outputHandler->render($rules);
    }
}