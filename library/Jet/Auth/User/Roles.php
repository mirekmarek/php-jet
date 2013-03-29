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
 * @subpackage Auth_User
 */
namespace Jet;

class Auth_User_Roles extends DataModel_Related_MtoN {
	/**
	 * @var string
	 */
	protected static $__data_model_model_name = "Jet_Auth_User_Roles";

	/**
	 * @var string
	 */
	protected $__data_model_M_model_class_name = "Jet\\Auth_User_Default";
	/**
	 * @var string
	 */
	protected $__data_model_N_model_class_name = "Jet\\Auth_Role_Default";
}