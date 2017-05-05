<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Auth_Role_Privilege_AvailablePrivilegesListItem
 * @package Jet
 */
class Auth_Role_Privilege_AvailablePrivilegesListItem extends BaseObject
{
	/**
	 * @var string
	 */
	public $privilege = '';
	/**
	 * @var string
	 */
	public $label = '';
	/**
	 * @var array|null
	 */
	public $values_list = null;

	/**
	 * @param string                                $privilege
	 * @param string                                $label
	 * @param array|Data_Tree_Forest|Data_Tree|null $values_list
	 */
	public function __construct( $privilege, $label, $values_list = null )
	{
		$this->privilege = $privilege;
		$this->label = $label;
		$this->values_list = $values_list;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getPrivilege()
	{
		return $this->privilege;
	}

	/**
	 * @return array|null
	 */
	public function getValuesList()
	{
		return $this->values_list;
	}
}