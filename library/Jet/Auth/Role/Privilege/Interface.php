<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
interface Auth_Role_Privilege_Interface
{

	/**
	 * @return string
	 */
	public function getPrivilege(): string;

	/**
	 * @param string $privilege
	 */
	public function setPrivilege( string $privilege ): void;

	/**
	 * @return array
	 */
	public function getValues(): array;

	/**
	 * @param array $values
	 */
	public function setValues( array $values );

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function hasValue( mixed $value ): bool;

}