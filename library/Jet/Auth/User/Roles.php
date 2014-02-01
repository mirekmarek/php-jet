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
 * Class Auth_User_Roles
 *
 * @JetDataModel:name = 'Jet_Auth_User_Roles'
 *
 * @JetDataModel:M_model_class_name = 'Jet\\Auth_User_Default'
 * @JetDataModel:N_model_class_name = 'Jet\\Auth_Role_Default'
 */
class Auth_User_Roles extends DataModel_Related_MtoN {
}