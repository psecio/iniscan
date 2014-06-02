<?php

namespace Psecio\Iniscan\Command\ScanCommand\Output;

class Console extends \Psecio\Iniscan\Command\Output
{
    public function render($results)
    {
        $output = $this->getOutput();
        $path = $this->getOption('path');
        $failOnly = $this->getOption('failOnly');
        $deprecated = $this->getOption('deprecated');
        $verbose = $this->getOption('verbose');

        $output->writeLn("<fg=cyan>== Executing INI Scan [".date('m.d.Y H:i:s')."] ==</fg=cyan>");

        // loop through the results and output color coded
        $output->writeLn("\nResults for ".$path.":\n".str_repeat('=', 12));
        $output->writeLn(
            str_pad("Status", 7, ' ').'| '
            .str_pad("Severity", 9, ' ').'| '
            .str_pad("PHP Version", 12, ' ').'| '
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
                $fgcolor = 'black';
                $bgcolor = ($severity == 'WARNING') ? 'yellow' : 'red';
                $status = 'FAIL';
            } elseif ($result->getStatus() === null) {
                $fgcolor = 'magenta';
                $status = 'N/A';
                $bgcolor = 'black';
            } else {
                $pass++;
                $status = 'PASS';
                $fgcolor = 'green';
                $bgcolor = 'black';
            }
            if ($failOnly === true && $status !== 'FAIL') {
                continue;
            }
            $test = $result->getTest();
            $version = (isset($test->version)) ? $test->version : '';
            $test = (isset($test->key)) ? $test->key : '';

            $output->writeLn(
                '<fg='.$fgcolor.';bg='.$bgcolor.'>'
                .str_pad($status, 7, ' ')
                .'| '.str_pad($result->getLevel(), 9, ' ')
                .'| '.str_pad($version, 12, ' ')
                .'| '.str_pad($test, 25, ' ')
                .'| '.$result->getDescription()
                .'</fg='.$fgcolor.';bg='.$bgcolor.'>'
                );

            if ($verbose === true && isset($result->info)) {
                $output->writeLn('INFO: '.$result->info."\n");
            }
        }
        $output->writeLn("\n<info>".$pass." passing</info>\n<error>".$fail." failure(s)</error>");

        if (!empty($deprecated)) {
            $output->writeLn("\n<error>WARNING: deprecated configuration items found:</error>");
            foreach ($deprecated as $dep) {
                $output->writeLn('<fg=yellow>-> '.$dep.'</fg=yellow>');
            }
            $output->writeLn("It's recommended that these settings be removed "
                ."as they will be removed from future PHP versions.\n");
        }

        return (count($fail) > 0) ? 1 : 0;
    }
}