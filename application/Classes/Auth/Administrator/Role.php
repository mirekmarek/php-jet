<?php
namespace JetApplication;


use Jet\Application_Modules;
use Jet\DataModel;
use Jet\DataModel_Id_AutoIncrement;
use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role;
use Jet\Data_Tree_Forest;
use Jet\Data_Tree;
use Jet\Tr;
use Jet\Mvc;

/**
 *
 * @JetDataModel:database_table_name = 'roles_administrators'
 *
 */
class Auth_Administrator_Role extends Auth_Role
{

	/**
	 * @var array
	 */
	protected static $standard_privileges = [
		self::PRIVILEGE_VISIT_PAGE => [
			'label'                                 => 'Administration sections',
			'get_available_values_list_method_name' => 'getAclActionValuesList_Pages',
		],

		self::PRIVILEGE_MODULE_ACTION => [
			'label'                                 => 'Modules and actions',
			'get_available_values_list_method_name' => 'getAclActionValuesList_ModulesActions',
		],

	];


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_Administrator_Role_Users'
	 *
	 * @var Auth_Administrator_Role_Users|DataModel_Related_MtoN_Iterator|Auth_Administrator_User[]
	 */
	protected $users;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_Administrator_Role_Privilege'
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var Auth_Administrator_Role_Privilege[]
	 */
	protected $privileges;

	/**
	 * Get Modules and actions ACL values list
	 *
	 * @return Data_Tree_Forest
	 */
	public static function getAclActionValuesList_ModulesActions()
	{

		$forest = new Data_Tree_Forest();
		$forest->setLabelKey( 'name' );
		$forest->setIdKey( 'id' );

		$modules = Application_Modules::getActivatedModulesList();

		foreach( $modules as $module_name => $module_info ) {

			$module = Application_Modules::getModuleInstance( $module_name );

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
	 *
	 * @return Mvc_Page[]
	 */
	public static function getAclActionValuesList_Pages()
	{
		$pages = [];

		foreach( Mvc_Page::getList( Mvc::getCurrentSite()->getId(), Mvc::getCurrentLocale() ) as $page ) {
			/**
			 * @var Mvc_Page $page
			 */
			if( !$page->getIsAdminUI() ) {
				continue;
			}

			if( $page->getIsDialog()||$page->getIsSystemPage() ) {
				continue;
			}
			$pages[$page->getId()] = $page->getTitle();
		}

		asort( $pages );

		return $pages;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}