<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_User
 */
namespace Jet;

/**
 *
 * @JetDataModel:name = 'role_users'
 *
 * @JetDataModel:database_table_name = 'Jet_Auth_Users_Roles'
 *
 * @JetDataModel:M_model_class_name = JET_AUTH_ROLE_CLASS
 * @JetDataModel:N_model_class_name = JET_AUTH_USER_CLASS
 */
class Auth_Role_Users extends DataModel_Related_MtoN {

	/**
	 * @JetDataModel:related_to = 'user.ID'
	 */
	protected $user_ID = '';

	/**
	 * @JetDataModel:related_to = 'role.ID'
	 */
	protected $role_ID = '';


}