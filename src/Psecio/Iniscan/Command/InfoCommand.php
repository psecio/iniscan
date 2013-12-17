<?php
namespace Psecio\Iniscan\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class InfoCommand extends Command
{
    protected function configure()
    {
        $this->setName('info')
            ->setDescription('Provide more detail on the requested setting')
            ->setDefinition(array(
                new InputOption('setting', 'setting', InputOption::VALUE_REQUIRED, 'INI Setting name'),
            ))
            ->setHelp(
                'Provides more information about the security implications around '
                .'a given PHP.ini setting.'
            );
    }

    /**
     * Execute the "info" command
     *
     * @param  InputInterface $input Input object
     * @param  OutputInterface $output Output object
     * @throws \Exception
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $setting = $input->getOption('setting');
        $options = array();

        if ($setting === null) {
            throw new \Exception('No setting provided!');
        }

        $scan = new \Psecio\Iniscan\Scan();
        $ruleSet = $scan->getRules();

        // see if we can find info about the setting
        $found = array();
        foreach ($ruleSet as $section => $rules) {
            foreach ($rules as $rule) {
                if (isset($rule->test->key) && $rule->test->key === $setting) {
                    if (isset($rule->info)) {
                        $found[] = $rule;
                    }
                }
            }
        }

        if (!empty($found)) {
            $outputHandler = new \Psecio\Iniscan\Command\InfoCommand\Output\Console($output, $options);
            return $outputHandler->render($found);
        } else {
            $output->writeLn('No information found for setting '.$setting);
        }
    }
}

?>
