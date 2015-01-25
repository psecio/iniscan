<?php

namespace Psecio\Iniscan\Command\InfoCommand\Output;

class Console extends \Psecio\Iniscan\Command\Output
{
    public function render($results)
    {
    	$output = $this->getOutput();

    	$setting = $results[0]->test->key;
    	$output->writeLn("<fg=cyan>== Information for setting: ".$setting." ==</fg=cyan>\n");
    	foreach ($results as $result) {
    		$output->writeLn($result->info);
    	}
    	$output->writeLn("\n");
    }
}
