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
 * @subpackage Auth_User
 */
namespace JetExampleApp;

use Jet\Auth_User_Roles;

/**
 *
 *
 * @JetDataModel:database_table_name = 'users_administrators_roles'
 *
 * @JetDataModel:M_model_class_name = 'JetExampleApp\Auth_Administrator_User'
 * @JetDataModel:N_model_class_name = 'JetExampleApp\Auth_Administrator_Role'
 */
class Auth_Administrator_User_Roles extends Auth_User_Roles {
}