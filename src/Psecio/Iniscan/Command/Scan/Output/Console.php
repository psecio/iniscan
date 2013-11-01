<?php

namespace Psecio\Iniscan\Command\Scan\Output;

class Console extends \Psecio\Iniscan\Command\Output
{
    public function render($results)
    {
        $output = $this->getOutput();
        $path = $this->getOption('path');
        $failOnly = $this->getOption('failOnly');

        // loop through the results and output color coded
        $output->writeLn("\nResults for ".$path.":\n".str_repeat('=', 12));
        $output->writeLn(
            str_pad("Status", 7, ' ').'| '
            .str_pad("Severity", 9, ' ').'| '
            .str_pad("Key", 25, ' ').'| Description'
        );
        $output->writeLn(str_repeat('-', 70));
        $fail = 0;
        $pass = 0;

        foreach ($results as $result) {
            if ($result->getStatus() === false) {
                $fail++;
                // if we failed, see how bad it is
                $severity = $result->getLevel();
                $color = ($severity == 'WARNING') ? 'yellow' : 'red';
                $status = 'FAIL';
            } else {
                $pass++;
                $status = 'PASS';
                $color = 'green';
            }
            if ($failOnly === true && $status !== 'FAIL') {
                continue;
            }
            $test = $result->getTest();
            $test = (isset($test->key)) ? $test->key : '';

            $output->writeLn(
                '<fg='.$color.'>'
                .str_pad($status, 7, ' ')
                .'| '.str_pad($result->getLevel(), 9, ' ')
                .'| '.str_pad($test, 25, ' ')
                .'| '.$result->getDescription()
                .'</fg='.$color.'>'
                );
        }
        $output->writeLn("\n<info>".$pass." passing</info>\n<error>".$fail." failure(s)</error>");

        return (count($fail) > 0) ? 1 : 0;
    }
}