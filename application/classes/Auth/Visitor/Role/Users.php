<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Auth_Role_Users;

/**
 *
 *
 * @JetDataModel:database_table_name = 'users_visitors_roles'
 *
 * @JetDataModel:M_model_class_name = 'Auth_Visitor_Role'
 * @JetDataModel:N_model_class_name = 'Auth_Visitor_User'
 */
class Auth_Visitor_Role_Users extends Auth_Role_Users
{
}