<?php

namespace Psecio\Iniscan\Command\ListCommand\Output;

class Console extends \Psecio\Iniscan\Command\Output
{
    public function render($rules)
    {
    	$output = $this->getOutput();
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