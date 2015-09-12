<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\JetExample\Images
 */
namespace JetApplicationModule\JetExample\Images;
use Jet;

class Controller_Admin_AJAX extends Jet\Mvc_Controller_AJAX {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;


	protected static $ACL_actions_check_map = array(
		'default' => false
	);

	/**
	 *
	 */
	public function initialize() {
	}

	function default_Action() {

		$article = new Gallery();
		$form = $article->getCommonForm();
		$form->enableDecorator('Dojo');

		$this->view->setVar('form', $form);

		//TODO: $this->view->setVar('upload_URL', $this->module_instance->getRestURL('image'));

		$upload_form = new Jet\Form('upload_form', array());
		$upload_form->enableDecorator('Dojo');
		$upload_form->addField(
			Jet\Form_Factory::field('Checkbox', 'overwrite_if_exists', 'Overwrite image if exists')
		);
		$this->view->setVar('upload_form', $upload_form);


		$this->render('ria/default');
	}

}