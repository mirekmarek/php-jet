<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\DataModel_Related_MtoN;
use Jet\DataModel_Definition;

/**
 *
 */
#[DataModel_Definition(name: 'users_roles')]
#[DataModel_Definition(database_table_name: 'users_visitors_roles')]
#[DataModel_Definition(parent_model_class: Auth_Visitor_User::class)]
#[DataModel_Definition(N_model_class: Auth_Visitor_Role::class)]
class Auth_Visitor_User_Roles extends DataModel_Related_MtoN
{
	/**
	 */
	#[DataModel_Definition(related_to: 'main.id')]
	protected int $user_id = 0;

	/**
	 */
	#[DataModel_Definition(related_to: 'role.id')]
	protected string $role_id = '';
}