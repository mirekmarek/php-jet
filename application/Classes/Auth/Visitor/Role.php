<?php
namespace JetApplication;

use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role;
use Jet\Mvc_Page_Interface;
use Jet\Data_Tree;
use Jet\Data_Forest;
use Jet\DataModel;
use Jet\DataModel_Id_AutoIncrement;
use Jet\Form;


/**
 *
 * @JetDataModel:database_table_name = 'roles_visitors'
 */
class Auth_Visitor_Role extends Auth_Role
{

	/**
	 * @var array
	 */
	protected static $privilege_set = [
		self::PRIVILEGE_VISIT_PAGE => [
			'label'                                 => 'Secret area access',
			'get_available_values_list_method_name' => 'getAclActionValuesList_Pages',
		],

	];

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_Visitor_Role_Users'
	 *
	 * @var Auth_Visitor_Role_Users|DataModel_Related_MtoN_Iterator|Auth_Visitor_User[]
	 */
	protected $users;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_Visitor_Role_Privilege'
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var Auth_Visitor_Role_Privilege[]
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
	 * Get sites and pages ACL values list
	 *
	 * @return Data_Forest
	 */
	public static function getAclActionValuesList_Pages()
	{

		$forest = new Data_Forest();
		$forest->setIdKey( 'id' );
		$forest->setLabelKey( 'name' );


		$site = Application::getWebSite();
		foreach( $site->getLocales() as $locale ) {

			$homepage = $site->getHomepage( $locale );

			$tree = new Data_Tree();
			$tree->setAdoptOrphans( true );

			$tree->getRootNode()->setId( $homepage->getKey() );
			$tree->getRootNode()->setLabel(
				$homepage->getSite()->getName().' ('.$homepage->getLocale()->getName().')'.' - '.$homepage->getName(
				)
			);

			$pages = [];
			foreach( $homepage->getChildren() as $page ) {
				static::_getPagesTree( $page, $pages );
			}

			$tree->setData( $pages );

			$forest->appendTree( $tree );
		}



		foreach( $forest as $node ) {
			//$node->setLabel( $node->getLabel().' ('.$node->getId().')' );

			if( $node->getIsRoot() ) {
				$node->setSelectOptionCssStyle( 'font-weight:bolder;font-size:15px;padding: 3px;' );
			} else {
				$padding = 20*$node->getDepth();
				$node->setSelectOptionCssStyle(
					'padding-left: '.$padding.'px;padding-top:2px; padding-bottom:2px; font-size:12px;'
				);
			}

		}

		return $forest;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 * @param                    $data
	 */
	protected static function _getPagesTree( Mvc_Page_Interface $page, &$data )
	{

		if($page->isSecret()) {
			$data[$page->getKey()] = [
				'id'        => $page->getKey(),
				'parent_id' => $page->getParent()->getKey(),
				'name'      => $page->getName(),
			];
		}


		foreach( $page->getChildren() as $page ) {
			static::_getPagesTree( $page, $data );
		}
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