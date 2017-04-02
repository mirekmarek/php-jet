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

use Jet\Auth_Role_Users;

/**
 *
 *
 * @JetDataModel:database_table_name = 'visitors_roles'
 *
 * @JetDataModel:M_model_class_name = 'JetExampleApp\Auth_Visitor_Role'
 * @JetDataModel:N_model_class_name = 'JetExampleApp\Auth_Visitor_User'
 */
class Auth_Visitor_Role_Users extends Auth_Role_Users  {
}