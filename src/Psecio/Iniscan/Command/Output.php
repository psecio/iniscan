<?php

namespace Psecio\Iniscan\Command;

abstract class Output
{
    /**
     * Output object
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * Output options
     * @var array
     */
    private $options;

    /**
     * Init the object and set output and options (if given)
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output Output object
     * @param array $options Output options
     */
    public function __construct(
        \Symfony\Component\Console\Output\OutputInterface $output,
        $options = array()
    )
    {
        $this->setOutput($output);
        $this->setOptions($options);
    }

    /**
     * Set the Output object instance
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output Object instance
     */
    public function setOutput(\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Get the Output instance
     *
     * @return object Output instance
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set the output options
     *
     * @param array $options Set of options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * Get the current set of options
     *
     * @return array Options set
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get a single option
     *
     * @param string $optionName Name of option to find
     * @return mixed Either the option value if found or null
     */
    public function getOption($optionName)
    {
        return (isset($this->options[$optionName]))
            ? $this->options[$optionName] : null;
    }

    /**
     * Render the results of the scan
     *
     * @param array $results Set of scan results
     */
    public abstract function render($results);
}
