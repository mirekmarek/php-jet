<?php
/**
 *
 *
 *
 * Default admin UI module
 *
 * @see Jet\Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_DefaultAdminUI
 * @subpackage JetApplicationModule_DefaultAdminUI_Controller
 */
namespace JetApplicationModule\JetExample\AdminUI;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = array(
        'logout' => false,
		'default' => false,
		'signpost' => false,
		'ria_default' => false,
		'classic_default' => false
	);

	/**
	 *
	 */
	public function initialize() {
        $JetML_postprocessor = Jet\Mvc::getCurrentPage()->getLayout()->enableJetML();
        $JetML_postprocessor->setIconsURL( $this->module_instance->getPublicURI().'icons/' );
        $JetML_postprocessor->setFlagsURL( $this->module_instance->getPublicURI().'flags/' );
	}

    /**
     *
     */
    public function logout_Action() {
        Jet\Auth::logout();

        Jet\Http_Headers::movedTemporary( Jet\Mvc_Page::get('admin')->getURL() );
    }

    /**
     *
     */
    public function signpost_Action() {
		$this->render('signpost');
	}

    /**
     *
     */
    public function classic_default_Action() {
        Jet\Mvc::getCurrentPage()->breadcrumbNavigationShift( -1 );

		$this->render('classic/default');

	}

    /**
     *
     */
    public function ria_default_Action() {


        $Dojo = new Jet\Javascript_Lib_Dojo();

		$Dojo->requireComponent('dojo.store.JsonRest');
		$Dojo->requireComponent('dojo.data.ObjectStore');
		$Dojo->requireComponent('dojo.data.ItemFileWriteStore');
		$Dojo->requireComponent('dijit.tree.TreeStoreModel');
		$Dojo->requireComponent('dijit.Tree');
		$Dojo->requireComponent('dijit.form.TextBox');
		$Dojo->requireComponent('dijit.form.FilteringSelect');
		$Dojo->requireComponent('dijit.form.MultiSelect');
		$Dojo->requireComponent('dijit.form.CheckBox');
		$Dojo->requireComponent('dijit.form.NumberTextBox');
		$Dojo->requireComponent('dijit.form.SimpleTextarea');
		$Dojo->requireComponent('dijit.TooltipDialog');
		$Dojo->requireComponent('dojox.grid.EnhancedGrid', array('css'=>array(
			'dojox/grid/enhanced/resources/%THEME%/EnhancedGrid.css',
			'dojox/grid/enhanced/resources/EnhancedGrid_rtl.css',
		)));


		$Dojo->requireComponent('dojox.form.BusyButton');
		$Dojo->requireComponent('dojox.grid.enhanced.plugins.Pagination');
		$Dojo->requireComponent('dojox.grid.enhanced.plugins.IndirectSelection');

        Jet\Mvc::requireJavascriptLib( $Dojo );



        $Jet = new Jet\Javascript_Lib_Jet();
        $Jet->setAJAXBaseURL( Jet\Mvc_Page::get('admin/ria/ajax')->getURL() );
        $Jet->setRESTBaseURL( Jet\Mvc_Page::get('admin/ria/rest_api')->getURL() );
        $Jet->setComponentsBaseURL( Jet\Mvc_Page::get('admin/ria/js')->getURL() );
        $Jet->setUIModuleName( $this->module_manifest->getDottedName() );

		$Jet->requireComponent('Jet.Form');
		$Jet->requireComponent('Jet.Trash');


        Jet\Mvc::requireJavascriptLib( $Jet );


        $TinyMCE = new Jet\Javascript_Lib_TinyMCE();

        Jet\Mvc::requireJavascriptLib( $TinyMCE );


		$this->render('ria/default');
	}

}