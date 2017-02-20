<?php

namespace Psecio\Iniscan\Command\ScanCommand\Output;

class Html extends \Psecio\Iniscan\Command\Output
{
	/**
	 * @param \Psecio\Iniscan\Rule[] $results
	 */
	public function render($results)
	{
		$output = $this->getOption('output');

		$outputDir = dirname($output);
		$outputFilename = basename($output);

		// Find out if a filename has been given in the output argument. If not, create the default output filename.
		// this checks for *.htm*
		if (strpos($outputFilename, ".htm") === FALSE) {
			// To keep backward compatibility, use the value of
			//the given output argument as the directory name.
			$outputDir = $output;
			$outputFilename = 'iniscan-output-'.date('Ymd').'.html';
		}

		// Check if the configured output directory exists. If not, create it.
		if (!is_dir($outputDir)) {
			mkdir($outputDir, 0777, true);
		}

		$outputFilePath = $outputDir . DIRECTORY_SEPARATOR . $outputFilename;

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

		if (is_writable($outputDir)) {
			foreach ($values as $key => $value) {
				$template = str_replace('{{'.$key.'}}', $value, $template);
			}
			file_put_contents($outputFilePath, $template);
		}
	}
}
