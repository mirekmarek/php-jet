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
 */
namespace JetApplicationModule\JetExample\Images;
use Jet;
use Jet\Mvc_Controller_AJAX;
use Jet\Mvc_Page;
use Jet\Form_Factory;
use Jet\Form;

class Controller_Admin_AJAX extends Mvc_Controller_AJAX {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;


	protected static $ACL_actions_check_map = [
		'default' => false
	];

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

		$this->view->setVar('upload_URL', Mvc_Page::get('admin/ria/rest_api')->getURI([], [
            $this->module_manifest->getName(),
            'image'
		]));

		$upload_form = new Form('upload_form', []);
		$upload_form->enableDecorator('Dojo');
		$upload_form->addField(
			Form_Factory::field(Form::TYPE_CHECKBOX, 'overwrite_if_exists', 'Overwrite image if exists')
		);
		$this->view->setVar('upload_form', $upload_form);


		$this->render('ria/default');
	}

}