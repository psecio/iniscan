<?php
namespace Psecio\Iniscan\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ScanCommand extends Command
{
    protected function configure()
    {
        $this->setName('scan')
            ->setDescription('Scan the given php.ini')
            ->setDefinition(array(
                new InputOption('path', 'path', InputOption::VALUE_OPTIONAL, 'Path to the php.ini'),
                new InputOption('fail-only', 'fail-only', InputOption::VALUE_NONE, 'Show only failing checks'),
                new InputOption('format', 'format', InputOption::VALUE_OPTIONAL, 'Output format'),
                new InputOption('context', 'context', InputOption::VALUE_OPTIONAL, 'Environment context (ex. "prod")'),
                new InputOption('threshold', 'threshold', InputOption::VALUE_OPTIONAL, 'Allows to show only things at or above this theshold'),
                new InputOption('php', 'php-version', InputOption::VALUE_OPTIONAL, 'Which version of PHP to evaulate'),
                new InputOption('output', 'output', InputOption::VALUE_OPTIONAL, 'Directory for file output types')
            ))
            ->setHelp(
                'Execute the scan on the php.ini for security best practices'
            );
    }

    /**
     * Execute the "scan" command
     *
     * @param  InputInterface $input Input object
     * @param  OutputInterface $output Output object
     * @throws \Psecio\Iniscan\Exceptions\FormatNotFoundException
     * @throws \Exception
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getOption('path');
        $failOnly = $input->getOption('fail-only');
        $format = $input->getOption('format');
        $context = $input->getOption('context');
        $threshold = $input->getOption('threshold');
        $version = $input->getOption('php');
        $outputPath = $input->getOption('output');

        if ($format === 'html' && $outputPath === null) {
            throw new \InvalidArgumentException('Output path must be set for format "HTML"');
        }

        $context = ($context !== null)
            ? explode(', ', $context) : array();

        // If we're not given a version, assume the current version
        if ($version === null) {
            $version = PHP_VERSION;
        }

        // if we're not given a path at all, try to figure it out
        if ($path === null) {
            $path = php_ini_loaded_file();
        }

        if (!is_file($path)) {
            throw new \Exception('Path is null or not accessible: "'.$path.'"');
        }

        $scan = new \Psecio\Iniscan\Scan($path, $context, $threshold, $version);
        $results = $scan->execute();
        $deprecated = $scan->getMarked();

        $options = array(
            'path' => $path,
            'failOnly' => $failOnly,
            'deprecated' => $deprecated,
            'verbose' => $input->getOption('verbose'),
            'output' => $outputPath
        );

        $format = ($format === null) ? 'console' : $format;
        $formatClass = "\\Psecio\\Iniscan\\Command\\ScanCommand\\Output\\".ucwords(strtolower($format));
        if (!class_exists($formatClass)) {
            throw new \Psecio\Iniscan\Exceptions\FormatNotFoundException('Output format "'.$format.'" not found');
        }
        $outputHandler = new $formatClass($output, $options);
        return $outputHandler->render($results);
    }
}

?>
