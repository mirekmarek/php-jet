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
namespace JetApplicationModule\JetExample\FrontController\Admin;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = array(
		'default' => false,
		'signpost' => false,
		'ria_default' => false,
		'classic_default' => false
	);

	public function signpost_Action() {
		$this->render('signpost');
	}

	public function classic_default_Action() {
		$this->getFrontController()->breadcrumbNavigationShift( -1 );

		$this->render('classic/default');

	}

	public function ria_default_Action() {
		/**
		 * @var Jet\Javascript_Lib_General $general_JS
		 */
		/*
		$general_JS = Jet\Mvc::requireJavascriptLib('General');

		$general_JS->requireScriptURL( JET_PUBLIC_SCRIPTS_URI.'my_js_lib.js' );
		$general_JS->requireScriptURL( 'http://domain.tld/some/js_lib.js' );
		$general_JS->requireScriptCode(' alert('Hello World!'); ');
		*/

		$Dojo = Jet\Mvc::requireJavascriptLib('Dojo');

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

		$Jet = Jet\Mvc::requireJavascriptLib('Jet');
		$Jet->requireComponent('Jet.Form');
		$Jet->requireComponent('Jet.Trash');

		Jet\Mvc::requireJavascriptLib('cbtree');

		Jet\Mvc::requireJavascriptLib('TinyMCE');


		$this->render('ria/default');
	}

}