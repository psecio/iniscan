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
                new InputOption('path', 'path', InputOption::VALUE_OPTIONAL, 'Path to the php.ini'),
                new InputOption('fail-only', 'fail-only', InputOption::VALUE_NONE, 'Show only failing checks')
            ))
            ->setHelp(
                'Execute the scan on the php.ini for security best practices'
            );
    }

    /**
     * Execute the "scan" command
     * 
     * @param  InputInterface  $input  Input object
     * @param  OutputInterface $output Output object
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getOption('path');
        $failOnly = $input->getOption('fail-only');

        // if we're not given a path at all, try to figure it out
        if ($path === null) {
            exec("php -i | grep 'Loaded Configuration'", $return);
            $return = preg_match('/Loaded Configuration File => (.*)$/', $return[0], $match);
            if (isset($match[1])) {
                $path = trim($match[1]);
            }
        }

        if (!is_file($path)) {
            throw new \Exception('Path is null or not not accessible: "'.$path.'"');
        }

        $scan = new \Psecio\Iniscan\Scan($path);
        $results = $scan->execute();

        // loop through the results and output color coded
        $output->writeLn("\nResults for ".$path.":\n".str_repeat('=', 12));
        $output->writeLn(
            str_pad("Status", 7, ' ').'| '
            .str_pad("Severity", 9, ' ').'| '
            .str_pad("Key", 25, ' ').'| Name'
        );
        $output->writeLn(str_repeat('-', 70));
        $fail = 0;
        $pass = 0;

        foreach ($results as $result) {
            if ($result->getStatus() === false) {
                $fail++;
                // if we failed, see how bad it is
                $severity = $result->getLevel();
                $color = ($severity == 'WARNING') ? 'yellow' : 'red';
                $status = 'FAIL';
            } else {
                $pass++;
                $status = 'PASS';
                $color = 'green';
            }
            if ($failOnly === true && $status !== 'FAIL') {
                continue;
            }
            $test = $result->getTest();
            $test = (isset($test->key)) ? $test->key : '';

            $output->writeLn(
                '<fg='.$color.'>'
                .str_pad($status, 7, ' ')
                .'| '.str_pad($result->getLevel(), 9, ' ')
                .'| '.str_pad($test, 25, ' ')
                .': '.$result->getDescription()
                .'</fg='.$color.'>'
                );
        }
        $output->writeLn("\n<info>".$pass." passing</info>\n<error>".$fail." failure(s)</error>");

        return;
    }
}

?>  