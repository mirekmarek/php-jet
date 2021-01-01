<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Exception;

/**
 *
 */
class ClassParser_Exception extends Exception{

	/**
	 * @var string
	 */
	protected string $invalid_source_code = '';

	/**
	 * @return string
	 */
	public function getInvalidSourceCode() : string
	{
		return $this->invalid_source_code;
	}

	/**
	 * @param string $invalid_source_code
	 */
	public function setInvalidSourceCode( string $invalid_source_code )
	{
		$this->invalid_source_code = $invalid_source_code;
	}



}