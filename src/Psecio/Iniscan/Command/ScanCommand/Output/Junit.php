<?php
/**
 * Created by Andreas Katzig.
 * Date: 20/02/2017
 * Time: 10:53
 */

namespace Psecio\Iniscan\Command\ScanCommand\Output;

/**
 * Creates a Junit compatible XML output.
 * XSD based on https://github.com/windyroad/JUnit-Schema/blob/master/JUnit.xsd.
 * @package Psecio\Iniscan\Command\ScanCommand\Output
 */
class Junit extends \Psecio\Iniscan\Command\Output
{
	/**
	 * @param \Psecio\Iniscan\Rule[] $results
	 */
	public function render(/*array*/ $results)
	{
		$xml = $this->getOutput();

		$failCount = 0;
		$testCount = 0;
		$errorCount = 0;
		$startTime = microtime(true);
		$errors = "";
		$output = "";

		$dom = new \DomDocument('1.0', 'UTF-8');

		$testSuite = $dom->createElement('testsuite');

		$properties = $dom->createElement('properties');
		$testSuite->appendChild($properties);

		foreach ($results as $result) {
			$testCount++;

			$currentValue = $result->getValue();
			$level = '(' . ucfirst(strtolower($result->getLevel())) . ' level)';

			$output .= 'Check ' . (($result->getStatus()) ? 'succeeded! ' : 'failed! ');
			$output .= $level . ' ' . $result->getName() . ': ' . $result->getDescription() . '. ';
			$output .= 'Current Value: ' . ((isset($currentValue)) ? $currentValue : 'not set.');
			$output .= "\n=======================================================================================\n";

			if ($result->getStatus() === null) {
				$errorCount++;
				$errors .= $output;
			}

			$testcase = $dom->createElement('testcase');

			if ($result->getStatus() === false) {
				$failCount++;
				$elem = $dom->createElement('failure', 'Current Value: ' . $currentValue);
				$elem->setAttribute('message', $level. ' ' . $result->getDescription());
				$elem->setAttribute('type', $result->getSection());
				$testcase->appendChild($elem);
			}

			$testcase->setAttribute('name', $result->getName());
			$testcase->setAttribute('time', 0);
			$testcase->setAttribute('classname', $result->getTestKey());

			$testSuite->appendChild($testcase);
		}

		$testSuite->setAttribute('name', 'iniscan - Scanner for PHP.ini');
		$testSuite->setAttribute('hostname', gethostname() ?: 'localhost');
		$testSuite->setAttribute('timestamp', strftime("%Y-%m-%dT%H:%M:%S"));
		$testSuite->setAttribute('failures', $failCount);
		$testSuite->setAttribute('errors', $errorCount);
		$testSuite->setAttribute('tests', $testCount);
		$testSuite->setAttribute('time', round((microtime(true) - $startTime), 6));

		$dom->appendChild($testSuite);

		$sysout = $dom->createElement('system-out', $output);
		$testSuite->appendChild($sysout);

		$syserr = $dom->createElement('system-err', $errors);
		$testSuite->appendChild($syserr);

		$xml->writeLn($dom->saveXML());
	}
}