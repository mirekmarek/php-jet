<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Exception;

class ClassParser_Exception extends Exception{

	/**
	 * @var string
	 */
	protected $invalid_source_code = '';

	/**
	 * @return string
	 */
	public function getInvalidSourceCode()
	{
		return $this->invalid_source_code;
	}

	/**
	 * @param string $invalid_source_code
	 */
	public function setInvalidSourceCode( $invalid_source_code )
	{
		$this->invalid_source_code = $invalid_source_code;
	}



}