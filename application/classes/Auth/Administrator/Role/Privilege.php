<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;
use Jet\Auth_Role_Privilege;

/**
 *
 * @JetDataModel:database_table_name = 'roles_administrators_privileges'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 * @JetDataModel:parent_model_class_name = 'JetExampleApp\Auth_Administrator_Role'
 */
class Auth_Administrator_Role_Privilege extends Auth_Role_Privilege
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