<?php
namespace Psecio\Iniscan\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ScanCommand extends Command
{
    protected function configure()
    {
        $this->setName('scan')
            ->setDescription('Scan the given php.ini')
            ->setDefinition(array(
                new InputOption('path', 'path', InputOption::VALUE_REQUIRED, 'Path to the php.ini')
            ))
            ->setHelp(
                'Execute the scan on the php.ini for security best practices'
            );
    }

    /**
     * Execute the filter command
     * 
     * @param  InputInterface  $input  Input object
     * @param  OutputInterface $output Output object
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getOption('path');
        if (!is_file($path)) {
            throw new \Exception('Path is null or not not accessible: "'.$path.'"');
        }

        $scan = new \Psecio\Iniscan\Scan($path);
        $results = $scan->execute();

        // loop through the results and output color coded
        $output->writeLn("\nResults for ".$path.":\n".str_repeat('=', 10));
        $output->writeLn(str_pad("LEVEL", 10, ' ').'| Name');
        $output->writeLn(str_repeat('-', 20));
        $fail = 0;
        $pass = 0;

        foreach ($results as $result) {
            if ($result->getStatus() === false) {
                $fail++;
                $color = 'red';
            } else {
                $pass++;
                $color = 'green';
            }
            $output->writeLn('<fg='.$color.'>'.str_pad($result->getLevel(), 10, ' ').'| '.$result->getName().'</fg='.$color.'>');
        }
        $output->writeLn("\n<info>".$pass." passing</info>\n<error>".$fail." failure(s)</error>");

        return;
    }
}

?>  