<?php

namespace Psecio\Iniscan\Command\ScanCommand\Output;

class Html extends \Psecio\Iniscan\Command\Output
{
    /**
     * @param \Psecio\Iniscan\Rule[] $results
     */
    public function render($results)
    {
        $output = $this->getOutput();
		$output = $this->getOption('output');

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

		if (is_writable($output)) {
			$output .= '/iniscan-output-'.date('Ymd').'.html';
			foreach ($values as $key => $value) {
				$template = str_replace('{{'.$key.'}}', $value, $template);
			}
			file_put_contents($output, $template);
		}
	}
}
