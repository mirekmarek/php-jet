<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;
use Jet\Auth_Role_Privilege;
use Jet\DataModel;
use Jet\DataModel_Id_AutoIncrement;

/**
 *
 * @JetDataModel:database_table_name = 'roles_visitors_privileges'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 * @JetDataModel:parent_model_class_name = 'Auth_Visitor_Role'
 */
class Auth_Visitor_Role_Privilege extends Auth_Role_Privilege
{
	/**
	 * @JetDataModel:related_to = 'main.id'
	 * @JetDataModel:form_field_type = false
	 */
	protected $role_id = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID_AUTOINCREMENT
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $id = 0;

}