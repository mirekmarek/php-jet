<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplication\Installer;


/**
 *
 */
class Installer_CompatibilityTester_TestResult
{
	/**
	 * @var bool
	 */
	protected bool $required = true;
	/**
	 * @var string
	 */
	protected string $title = '';
	/**
	 * @var string
	 */
	protected string $description = '';

	/**
	 * @var bool
	 */
	protected bool $passed = false;

	/**
	 * @var string
	 */
	protected string $result_message = '';

	/**
	 *
	 * @param bool $required
	 * @param string $title
	 * @param string $description
	 */
	public function __construct( bool $required, string $title, string $description )
	{
		$this->required = (bool)$required;
		$this->title = (string)$title;
		$this->description = (string)$description;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( string $description ): void
	{
		$this->description = (string)$description;
	}

	/**
	 * @return bool
	 */
	public function getPassed(): bool
	{
		return $this->passed;
	}

	/**
	 * @param bool $passed
	 */
	public function setPassed( bool $passed ): void
	{
		$this->passed = (bool)$passed;
	}

	/**
	 * @return bool
	 */
	public function getRequired(): bool
	{
		return $this->required;
	}

	/**
	 * @param bool $required
	 */
	public function setRequired( bool $required ): void
	{
		$this->required = (bool)$required;
	}

	/**
	 * @return string
	 */
	public function getResultMessage(): string
	{
		return $this->result_message;
	}

	/**
	 * @param string $result_message
	 */
	public function setResultMessage( string $result_message ): void
	{
		$this->result_message = (string)$result_message;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void
	{
		$this->title = (string)$title;
	}

	/**
	 * @return bool
	 */
	public function getIsError(): bool
	{
		return ($this->required && !$this->passed);
	}

	/**
	 * @return bool
	 */
	public function getIsWarning(): bool
	{
		return (!$this->required && !$this->passed);
	}
}
