<?php
namespace JetApplication;


use Jet\Application_Modules;
use Jet\Application_Module_Manifest;
use Jet\DataModel;
use Jet\DataModel_Id_AutoIncrement;
use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role;
use Jet\Data_Forest;
use Jet\Data_Tree;
use Jet\Tr;
use Jet\Form;

/**
 *
 * @JetDataModel:database_table_name = 'roles_rest_clients'
 *
 */
class Auth_RESTClient_Role extends Auth_Role
{

	/**
	 * @var array
	 */
	protected static $privilege_set = [

		self::PRIVILEGE_MODULE_ACTION => [
			'label'                                 => 'Modules and actions',
			'get_available_values_list_method_name' => 'getAclActionValuesList_ModulesActions',
		],

	];


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_RESTClient_Role_Users'
	 *
	 * @var Auth_RESTClient_Role_Users|DataModel_Related_MtoN_Iterator|Auth_RESTClient_User[]
	 */
	protected $users;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_RESTClient_Role_Privilege'
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var Auth_RESTClient_Role_Privilege[]
	 */
	protected $privileges;

	/**
	 * @var Form
	 */
	protected $_form_add;
	/**
	 * @var Form
	 */
	protected $_form_edit;


	/**
	 *
	 * @return Data_Forest
	 */
	public static function getAclActionValuesList_ModulesActions()
	{

		$forest = new Data_Forest();
		$forest->setLabelKey( 'name' );
		$forest->setIdKey( 'id' );

		$modules = Application_Modules::activatedModulesList();

		foreach( $modules as $module_name => $module_info ) {
			/**
			 * @var Application_Module_Manifest $module_info
			 */
			if(!$module_info->hasRestAPI()) {
				continue;
			}

			$module = Application_Modules::moduleInstance( $module_name );

			$actions = $module->getAclActions();

			if( !$actions ) {
				continue;
			}


			$data = [];


			foreach( $actions as $action => $action_description ) {
				$data[] = [
					'id'   => $module_name.':'.$action,
					'parent_id' => $module_name,
					'name' => Tr::_( $action_description, [], $module_info->getName() ),
				];
			}


			$tree = new Data_Tree();
			$tree->getRootNode()->setLabel( Tr::_( $module_info->getLabel(), [], $module_info->getName() ).' ('.$module_name.')' );
			$tree->getRootNode()->setId( $module_name );


			$tree->setData( $data );

			foreach( $tree as $node ) {
				if( $node->getIsRoot() ) {
					$node->setSelectOptionCssStyle( 'font-weight:bolder;font-size:15px;padding: 3px;' );
				} else {
					$node->setSelectOptionCssStyle(
						'padding-left: 20px;padding-top:2px; padding-bottom:2px; font-size:12px;'
					);
				}
			}

			$forest->appendTree( $tree );

		}

		return $forest;

	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return Form
	 */
	public function getEditForm()
	{
		if(!$this->_form_edit) {
			$this->_form_edit = $this->getCommonForm();
		}

		return $this->_form_edit;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		return $this->catchForm( $this->getEditForm() );
	}


	/**
	 * @return Form
	 */
	public function getAddForm()
	{
		if(!$this->_form_add) {
			$this->_form_add = $this->getCommonForm();
		}

		return $this->_form_add;
	}

	/**
	 * @return bool
	 */
	public function catchAddForm()
	{
		return $this->catchForm( $this->getAddForm() );
	}

}