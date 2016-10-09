<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Role
 */
namespace Jet;

interface Auth_Role_Privilege_Provider_Interface {

	/**
	 * @return Auth_Role_Privilege_AvailablePrivilegesListItem[]
	 */
	public function getAvailablePrivileges();
}