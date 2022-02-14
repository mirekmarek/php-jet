<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


interface Form_Field_Part_RegExp_Interface
{

	/**
	 *
	 * @param bool $raw
	 *
	 * @return string
	 */
	public function getValidationRegexp( bool $raw = false ): string;
	
	/**
	 *
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( string $validation_regexp ): void;
}