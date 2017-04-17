<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;


class CompatibilityTester_TestResult {
	/**
	 * @var bool
	 */
	protected $required = true;
	/**
	 * @var string
	 */
	protected $title = '';
	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var bool
	 */
	protected $passed = false;
	/**
	 * @var string
	 */
	protected $result_message = '';

	public function __construct( $required, $title, $description ) {
		$this->required = (bool)$required;
		$this->title = (string)$title;
		$this->description = (string)$description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = (string)$description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param bool $passed
	 */
	public function setPassed($passed) {
		$this->passed = (bool)$passed;
	}

	/**
	 * @return bool
	 */
	public function getPassed() {
		return $this->passed;
	}

	/**
	 * @param bool $required
	 */
	public function setRequired($required) {
		$this->required = (bool)$required;
	}

	/**
	 * @return bool
	 */
	public function getRequired() {
		return $this->required;
	}

	/**
	 * @param string $result_message
	 */
	public function setResultMessage($result_message) {
		$this->result_message = (string)$result_message;
	}

	/**
	 * @return string
	 */
	public function getResultMessage() {
		return $this->result_message;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = (string)$title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return bool
	 */
	public function getIsError() {
		return ($this->required && !$this->passed);
	}

	/**
	 * @return bool
	 */
	public function getIsWarning() {
		return (!$this->required && !$this->passed);
	}
}
