<?php

namespace Psecio\Iniscan\Command\Scan\Output;

class Json extends \Psecio\Iniscan\Command\Output
{
    public function render($results)
    {
        $output = $this->getOutput();
        $path = $this->getOption('path');
        $failOnly = $this->getOption('failOnly');

        $resultValues = array();
        foreach ($results as $result) {
        	$resultValues[] = $result->values();
        }

        $result = array('results' => $resultValues);

        $output->writeLn(json_encode($result));
    }
}