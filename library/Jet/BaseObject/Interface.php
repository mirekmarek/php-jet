<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
interface BaseObject_Interface
{


	/**
	 * @param string $property_name
	 *
	 * @return bool
	 */
	public function objectHasProperty( string $property_name ): bool;

	/**
	 * @param string $property_name
	 *
	 * @return string
	 */
	public function objectSetterMethodName( string $property_name ): string;


	/**
	 * @param string $property_name
	 *
	 * @return string
	 */
	public function objectGetterMethodName( string $property_name ): string;

}