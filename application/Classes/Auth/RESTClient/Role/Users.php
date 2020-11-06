<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\DataModel_Related_MtoN;

/**
 *
 *
 * @JetDataModel:name = 'roles_users'
 * @JetDataModel:database_table_name = 'users_rest_clients_roles'
 *
 * @JetDataModel:parent_model_class_name = 'Auth_RESTClient_Role'
 * @JetDataModel:N_model_class_name = 'Auth_RESTClient_User'
 */
class Auth_RESTClient_Role_Users extends DataModel_Related_MtoN
{
	/**
	 * @JetDataModel:related_to = 'user.id'
	 */
	protected $user_id = '';

	/**
	 * @JetDataModel:related_to = 'main.id'
	 */
	protected $role_id = '';
}