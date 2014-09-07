<?php

namespace Psecio\Iniscan\Command\ListCommand\Output;

class Json extends \Psecio\Iniscan\Command\Output
{
    public function render($rules)
    {
    	$output = $this->getOutput();

        $data = array();
        foreach ($rules as $section => $ruleSet) {
            foreach ($ruleSet as $rule) {
                $ruleKey = (isset($rule->test->key)) ? $rule->test->key : '';
                $data[$section][] = array(
                    'key' => $ruleKey,
                    'description' => $rule->description
                );
            }
        }

        $output->writeLn(json_encode($data));
    }
}
