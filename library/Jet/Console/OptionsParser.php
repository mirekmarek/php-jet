<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Console
 */

namespace Jet;

class Console_OptionsParser extends Object {
	/**
	 * @var Console_OptionsParser_OptionDefinition[]
	 */
	protected $options = array();

	/**
	 * @var bool
	 */
	protected $parsed_OK = false;

	/**
	 * @var string
	 */
	protected $help_header = "";

	/**
	 * @var string
	 */
	protected $help_footer = "";

	public function addOption( Console_OptionsParser_OptionDefinition $option ) {
		$this->options[$option->getName()] = $option;
	}

	/**
	 * @param string $help_footer
	 */
	public function setHelpFooter($help_footer) {
		$this->help_footer = (string)$help_footer;
	}

	/**
	 * @return string
	 */
	public function getHelpFooter()
	{
		return $this->help_footer;
	}

	/**
	 * @param string $help_header
	 */
	public function setHelpHeader($help_header) {
		$this->help_header = (string)$help_header;
	}

	/**
	 * @return string
	 */
	public function getHelpHeader() {
		return $this->help_header;
	}

	/**
	 * @return bool
	 */
	public function parse() {
		$short_options = "";
		$long_options = array();

		foreach( $this->options as $option ) {
			$short_options .= $option->getShortOptionDefinition();
			$long_options[] = $option->getLongOptionDefinition();
		}

		$values = getopt($short_options, $long_options);

		foreach( $this->options as $option ) {
		}

		var_dump($values);
		//TODO:
	}

	/**
	 *
	 */
	public function showHelp() {

		echo $this->help_header;
		echo "Options:".PHP_EOL;
		foreach( $this->options as $option ) {
			echo "\t--".$option->getOptionLong()."|-".$option->getOptionShort()." [".($option->getIsRequired()?"required":"optional")."] - ".$option->getHelp().PHP_EOL;
		}
		echo $this->help_footer;
	}

	/**
	 * @return array
	 */
	public function getOptions() {
		if(!$this->parsed_OK) {
			//TODO: throw new ...
		}
		//TODO:
	}
	//TODO:
}