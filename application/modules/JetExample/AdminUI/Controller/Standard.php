<?php
/**
 *
 * Default admin UI module
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminUI;
use Jet;
use Jet\Mvc_Controller_Standard;
use Jet\Auth;
use Jet\Mvc;
use Jet\Mvc_Page;
use Jet\Http_Headers;
use Jet\JavaScriptLib_Dojo;

class Controller_Standard extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = [
        'logout' => false,
		'default' => false,
		'signpost' => false,
		'ria_default' => false,
		'classic_default' => false
	];

	/**
	 *
	 */
	public function initialize() {
        $JetML_postprocessor = Mvc::getCurrentPage()->getLayout()->enableJetML();
        $JetML_postprocessor->setIconsURL( $this->module_manifest->getPublicURI().'icons/' );
        $JetML_postprocessor->setFlagsURL( $this->module_manifest->getPublicURI().'flags/' );
	}

    /**
     *
     */
    public function logout_Action() {
        Auth::logout();

        Http_Headers::movedTemporary( Mvc_Page::get('admin')->getURL() );
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
        Mvc::getCurrentPage()->breadcrumbNavigationShift( -1 );

		$this->render('classic/default');

	}

    /**
     *
     */
    public function ria_default_Action() {


        $Dojo = new JavaScriptLib_Dojo();

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
		$Dojo->requireComponent('dojox.grid.EnhancedGrid', ['css'=> [
			'dojox/grid/enhanced/resources/%THEME%/EnhancedGrid.css',
			'dojox/grid/enhanced/resources/EnhancedGrid_rtl.css',
		]]);


		$Dojo->requireComponent('dojox.form.BusyButton');
		$Dojo->requireComponent('dojox.grid.enhanced.plugins.Pagination');
		$Dojo->requireComponent('dojox.grid.enhanced.plugins.IndirectSelection');

        Mvc::requireJavascriptLib( $Dojo );



        $Jet = new JetJavaScriptLib();
        $Jet->setAJAXBaseURL( Mvc_Page::get('admin/ria/ajax')->getURL() );
        $Jet->setRESTBaseURL( Mvc_Page::get('admin/ria/rest_api')->getURL() );
        $Jet->setComponentsBaseURL( Mvc_Page::get('admin/ria/js')->getURL() );
        $Jet->setUIModuleName( $this->module_manifest->getName() );

		$Jet->requireComponent('Jet.Form');
		$Jet->requireComponent('Jet.Trash');

        Mvc::requireJavascriptLib( $Jet );


	    /*
        $TinyMCE = new JavaScriptLib_TinyMCE();

        Mvc::requireJavascriptLib( $TinyMCE );
		*/

		$this->render('ria/default');
	}

}