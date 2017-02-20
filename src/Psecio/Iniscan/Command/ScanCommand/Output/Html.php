<?php

namespace Psecio\Iniscan\Command\ScanCommand\Output;

class Html extends \Psecio\Iniscan\Command\Output
{
	/**
	 * @param \Psecio\Iniscan\Rule[] $results
	 */
	public function render($results)
	{
		$outputFilePath = $this->getOutputFilename($this->getOption('output'));

		// Check if the configured output directory exists. If not, create it.
		if (!is_dir(dirname($outputFilePath))) {
			mkdir(dirname($outputFilePath), 0777, true);
		}

		// read in the template file
		$template = file_get_contents(__DIR__.'/../Templates/html.html');

		$values = array(
			'date' => date('m.d.Y H:i:s'),
			'results' => ''
		);

		foreach ($results as $result) {
			$pass = ($result->getStatus() === true) ? 'pass' : 'fail';

			if ($result->getStatus() === null) {
			    $pass = 'warn';
			}

			$resultHtml = '<div class="result '.$pass.'">';
			$resultHtml .= '<table cellpadding="2" cellspacing="0" border="0" class="result">';
			$resultHtml .= '<tr><td class="key">'.$result->getTestKey();
			$resultHtml .= '<td>'.$result->getDescription().'</td></tr>';
			$resultHtml .= '</table></div><br/>';

			$values['results'] .= $resultHtml;
		}

		if (is_writable(dirname($outputFilePath))) {
			foreach ($values as $key => $value) {
				$template = str_replace('{{'.$key.'}}', $value, $template);
			}
			file_put_contents($outputFilePath, $template);
		}
	}

	/**
	 * Returns path and filename for the output file.
	 * @param $output The output path configured via its argument
	 */
	public function getOutputFilename($output)
	{
		// default behaviour / backwards compatibility
		$outputDir = $output;
		$outputFilename = $this->getDefaultOutputFilename();

		// Find out if a .htm(l) filename has been given in the output argument.
		if ($this->endsWith($output, ".htm") || $this->endsWith($output, ".html")) {
			$outputDir = dirname($output);
			$outputFilename = basename($output);
		}

		return $outputDir . DIRECTORY_SEPARATOR . $outputFilename;
	}

	public function getDefaultOutputFilename()
	{
		return 'iniscan-output-'.date('Ymd').'.html';
	}

	/**
	 * Return true if $haystack ends with $needle.
	 * Source: http://stackoverflow.com/a/834355
	 * @param $haystack
	 * @param $needle
	 * @return bool
	 */
	private function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}
}
