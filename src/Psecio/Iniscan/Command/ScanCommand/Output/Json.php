<?php

namespace Psecio\Iniscan\Command\ScanCommand\Output;

class Json extends \Psecio\Iniscan\Command\Output
{
    public function render($results)
    {
        $output = $this->getOutput();
        $resultValues = array();

        foreach ($results as $result) {
        	$resultValues[] = $result->values();
        }

        $result = array('results' => $resultValues);

        $output->writeLn(json_encode($result));
    }
}