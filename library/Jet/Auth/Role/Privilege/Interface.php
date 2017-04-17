<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Role
 */
namespace Jet;

interface Auth_Role_Privilege_Interface  {

	/**
	 * @return string
	 */
	public function getPrivilege();

	/**
	 * @param string $privilege
	 */
	public function setPrivilege($privilege);

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function getHasValue( $value );

	/**
	 * @return mixed[]
	 */
	public function getValues();

	/**
	 * @param array $values
	 */
	public function setValues(array $values);

}