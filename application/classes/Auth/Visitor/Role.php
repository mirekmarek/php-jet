<?php
namespace JetExampleApp;

use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role;
use Jet\Mvc_Site;
use Jet\Mvc_Page_Interface;
use Jet\Data_Tree;
use Jet\Data_Tree_Forest;


/**
 *
 * @JetDataModel:database_table_name = 'roles_visitors'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Visitor_Role extends Auth_Role{

	/**
	 * @var array
	 */
	protected static $standard_privileges = [
		self::PRIVILEGE_VISIT_PAGE => [
			'label' => 'Sites and pages',
			'get_available_values_list_method_name' => 'getAclActionValuesList_Pages'
		]

	];


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID_AUTOINCREMENT
	 * @JetDataModel:is_id = true
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'JetExampleApp\Auth_Visitor_Role_Users'
	 *
	 * @var Auth_Visitor_Role_Users|DataModel_Related_MtoN_Iterator|Auth_Visitor_User[]
	 */
	protected $users;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'JetExampleApp\Auth_Visitor_Role_Privilege'
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var Auth_Visitor_Role_Privilege[]
	 */
	protected $privileges;


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get sites and pages ACL values list
	 *
	 * @return Data_Tree_Forest
	 */
	public static function getAclActionValuesList_Pages() {

		$forest = new Data_Tree_Forest();
		$forest->setIdKey('id');
		$forest->setLabelKey('name');

		foreach( Mvc_Site::getList() as $site ) {
			foreach($site->getLocales() as $locale) {

				$homepage = $site->getHomepage( $locale );

				$tree = new Data_Tree();
				$tree->getRootNode()->setId( $homepage->getKey() );
				$tree->getRootNode()->setLabel(
					$homepage->getSite()->getName()
					.' ('.$homepage->getLocale()->getName().')'
					. ' - '
					.$homepage->getName()
				);

				$pages = [];
				foreach( $homepage->getChildren() as $page ) {
					static::_getAllPagesTree($page, $pages);
				}

				$tree->setData($pages);

				$forest->appendTree($tree);


			}
		}


		foreach( $forest as $node ) {
			//$node->setLabel( $node->getLabel().' ('.$node->getId().')' );

			if($node->getIsRoot()) {
				$node->setSelectOptionCssStyle('font-weight:bolder;font-size:15px;padding: 3px;');
			} else {
				$padding = 20*$node->getDepth();
				$node->setSelectOptionCssStyle('padding-left: '.$padding.'px;padding-top:2px; padding-bottom:2px; font-size:12px;');
			}

		}

		return $forest;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 * @param $data
	 */
	protected static function _getAllPagesTree( Mvc_Page_Interface $page, &$data ) {
		if($page->getIsAdminUI()) {
			return;
		}


		/**
		 * @var Mvc_Page $page
		 */
		$data[$page->getKey()] = [
			'id' => $page->getKey(),
			'parent_id' => $page->getParent()->getKey(),
			'name' => $page->getName()
		];

		foreach( $page->getChildren() as $page ) {
			static::_getAllPagesTree($page, $data);
		}
	}

}