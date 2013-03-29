<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Role
 */
namespace Jet;

class Auth_Role_Privilege_AvailablePrivilegesListItem extends Object {
	/**
	 * @var string
	 */
	public $privilege = "";
	/**
	 * @var string
	 */
	public $label = "";
	/**
	 * @var array|null
	 */
	public $values_list = null;

	/**
	 * @param string $privilege
	 * @param string $label
	 * @param array|null $values_list
	 */
	public function __construct( $privilege, $label, $values_list=null ) {
		$this->privilege = $privilege;
		$this->label = $label;
		$this->values_list = $values_list;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getPrivilege() {
		return $this->privilege;
	}

	/**
	 * @return array|null
	 */
	public function getValuesList() {
		return $this->values_list;
	}
}