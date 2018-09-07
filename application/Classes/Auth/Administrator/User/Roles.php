<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\DataModel_Related_MtoN;

/**
 *
 *
 * @JetDataModel:name = 'users_roles'
 * @JetDataModel:database_table_name = 'users_administrators_roles'
 *
 * @JetDataModel:parent_model_class_name = 'Auth_Administrator_User'
 * @JetDataModel:N_model_class_name = 'Auth_Administrator_Role'
 */
class Auth_Administrator_User_Roles extends DataModel_Related_MtoN
{
	/**
	 * @JetDataModel:related_to = 'main.id'
	 */
	protected $user_id = '';

	/**
	 * @JetDataModel:related_to = 'role.id'
	 */
	protected $role_id = '';

}