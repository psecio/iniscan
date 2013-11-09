<?php
namespace Psecio\Iniscan\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    protected function configure()
    {
        $this->setName('list')
            ->setDescription('Output information about the current rule checks')
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
        $scan = new \Psecio\Iniscan\Scan();
        $rules = $scan->getRules();

        $ruleCount = 0;
        $output->writeLn("\n<fg=yellow>Current tests:</fg=yellow>");
        foreach ($rules as $section => $ruleSet) {
            $output->writeLn('<info>'.$section.'</info>');
            foreach ($ruleSet as $rule) {
                $ruleCount++;
                $ruleKey = (isset($rule->test->key)) ? $rule->test->key : '[custom]';
                $output->writeLn('   '.str_pad($ruleKey, 30).'| '.$rule->description);
            }
        }
        $output->writeLn("\n<info>".$ruleCount.' tests</info>');
    }
}