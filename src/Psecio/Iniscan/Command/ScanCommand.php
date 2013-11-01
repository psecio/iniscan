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
            $path = php_ini_loaded_file();
        }

        if (!is_file($path)) {
            throw new \Exception('Path is null or not not accessible: "'.$path.'"');
        }

        $scan = new \Psecio\Iniscan\Scan($path);
        $results = $scan->execute();

        $options = array(
            'path' => $path,
            'failOnly' => $failOnly
        );

        $outputHandler = new \Psecio\Iniscan\Command\Scan\Output\Console($output, $options);
        return $outputHandler->render($results);
    }
}

?>