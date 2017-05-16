<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface Auth_Role_Privilege_Interface
{

	/**
	 * @param Auth_Role_Interface $role
	 */
	public function setRole( Auth_Role_Interface $role );

	/**
	 * @return string
	 */
	public function getPrivilege();

	/**
	 * @param string $privilege
	 */
	public function setPrivilege( $privilege );

	/**
	 * @return mixed[]
	 */
	public function getValues();

	/**
	 * @param array $values
	 */
	public function setValues( array $values );

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function hasValue( $value );

}