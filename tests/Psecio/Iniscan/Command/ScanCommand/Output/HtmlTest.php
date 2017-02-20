<?php
/**
 * Created by PhpStorm.
 * User: akatzig
 * Date: 20/02/2017
 * Time: 15:05
 */

namespace Psecio\Iniscan\Operation\ScanCommand\Output;


use Psecio\Iniscan\Command\ScanCommand\Output\Html;

class HtmlTest extends \PHPUnit_Framework_TestCase
{
	private $htmlOutput;
	private $defaultOutputFilename;

	public function setUp()
	{
		$outputInterfaceMock = $this->getMockBuilder(\Symfony\Component\Console\Output\OutputInterface::class)->getMock();
		$this->htmlOutput = new Html($outputInterfaceMock, null);
		$this->defaultOutputFilename = $this->htmlOutput->getDefaultOutputFilename();
	}

	/**
	 * Tests if the output file name is correctly recognized and generated.
	 */
	public function testOutputFilename()
	{
		$generatedFile = DIRECTORY_SEPARATOR . $this->defaultOutputFilename;

		$testValues = array(
			"/var/outputfolder" => "/var/outputfolder" . $generatedFile,
			"/folder/folder.ext" => "/folder/folder.ext" . $generatedFile,
			"/folder/folder.ext/abcde" => "/folder/folder.ext/abcde" . $generatedFile,
			"./folder/folder.ext/abcde..." => "./folder/folder.ext/abcde..." . $generatedFile,
			"/folder/folder.ext/filenamehtml" => "/folder/folder.ext/filenamehtml" . $generatedFile,
			"./folder/folder.ext/123.ehtml" => "./folder/folder.ext/123.ehtml" . $generatedFile,
			"../folder/folder.ext/123.html.a" => "../folder/folder.ext/123.html.a" . $generatedFile,
			"folder/folder.ext/123.html.a" => "folder/folder.ext/123.html.a" . $generatedFile,
			"folder/abc/1.ht" => "folder/abc/1.ht" . $generatedFile,
			"../folder/folder.ext/123.html" => "../folder/folder.ext/123.html",
			"/folder/folder.ext/123.abc.html" => "/folder/folder.ext/123.abc.html",
			"/folder/folder.ext/123.abc.htm" => "/folder/folder.ext/123.abc.htm",
			"/folder/abc/1.htm" => "/folder/abc/1.htm",
			"folder/abc/1.htm" => "folder/abc/1.htm"
		);

		foreach($testValues as $input => $expectedOutput) {
			$output = $this->htmlOutput->getOutputFilename($input);
			$this->assertSame($expectedOutput, $output);
		}

	}
}
