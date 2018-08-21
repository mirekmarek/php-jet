<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\DataModel_Related_MtoN;

/**
 *
 *
 * @JetDataModel:name = 'roles_users'
 * @JetDataModel:database_table_name = 'users_administrators_roles'
 *
 * @JetDataModel:M_model_class_name = 'Auth_Administrator_Role'
 * @JetDataModel:N_model_class_name = 'Auth_Administrator_User'
 */
class Auth_Administrator_Role_Users extends DataModel_Related_MtoN
{
	/**
	 * @JetDataModel:related_to = 'user.id'
	 */
	protected $user_id = '';

	/**
	 * @JetDataModel:related_to = 'role.id'
	 */
	protected $role_id = '';

}