<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\Jet\Images
 */
namespace JetApplicationModule\Jet\Images;
use Jet;

class Controller_AJAX extends Jet\Mvc_Controller_AJAX {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;


	protected static $ACL_actions_check_map = array(
		"default" => false
	);

	function default_Action() {
		$article = new Gallery();
		$form = $article->getCommonForm();
		$form->enableDecorator("Dojo");

		$this->view->setVar("form", $form);

		$this->view->setVar("upload_URL", $this->module_instance->getRestURL("image"));

		$upload_form = new Jet\Form("upload_form", array());
		$upload_form->enableDecorator("Dojo");
		$upload_form->addField(
			Jet\Form_Factory::field("Checkbox", "overwrite_if_exists", "Overwrite image if exists")
		);
		$this->view->setVar("upload_form", $upload_form);


		$this->render("admin-ajax");
	}

}