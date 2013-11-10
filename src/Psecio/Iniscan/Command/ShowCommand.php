<?php
namespace Psecio\Iniscan\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ShowCommand extends Command
{
    protected function configure()
    {
        $this->setName('show')
            ->setDescription('Show the current PHP configuration')
            ->setDefinition(array(
                new InputOption('path', 'path', InputOption::VALUE_OPTIONAL, 'Path to the php.ini')
            ))
            ->setHelp(
                'Execute the scan on the php.ini to show current settings'
            );
    }

    /**
     * Execute the "show" command
     *
     * @param  InputInterface  $input  Input object
     * @param  OutputInterface $output Output object
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getOption('path');

        // if we're not given a path at all, try to figure it out
        if ($path === null) {
            $path = php_ini_loaded_file();
        }

        if (!is_file($path)) {
            throw new \Exception('Path is null or not accessible: "'.$path.'"');
        }
        $ini = parse_ini_file($path, true);

        $output->writeLn('Current PHP.ini settings from '.$path);
        $output->writeLn('##########');

        foreach ($ini as $section => $data) {
            $output->writeLn('<info>:: '.$section.'</info>');
            if (empty($data)) {
                $output->writeLn("\t<fg=yellow>No settings</fg=yellow>");
            } else {
                foreach ($data as $path => $value) {
                    $output->writeLn("\t".$path.' => '.var_export($value, true));
                }
            }
            $output->writeLn("-----------------\n");
        }

    }
}