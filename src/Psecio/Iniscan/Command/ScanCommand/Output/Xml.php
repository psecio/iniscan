<?php

namespace Psecio\Iniscan\Command\ScanCommand\Output;

class Xml extends \Psecio\Iniscan\Command\Output
{
    public function render($results)
    {
        $output = $this->getOutput();
        $resultValues = array();

        foreach ($results as $result) {
        	$resultValues[] = $result->values();
        }

        $dom = new \DomDocument('1.0', 'UTF-8');
        $results = $dom->createElement('results');

        foreach ($resultValues as $result) {
            $resultXml = $dom->createElement('result');

            foreach ($result as $name => $value) {
                $property = $dom->createElement($name, $value);
                $resultXml->appendChild($property);
            }

            $results->appendChild($resultXml);
        }
        $dom->appendChild($results);

        $output->writeLn($dom->saveXML());
    }
}