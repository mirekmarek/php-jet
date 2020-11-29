<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\Form;
use Jet\Http_Request;
use Jet\IO_File;
use Jet\Mvc_View;
use Jet\Tr;

/**
 *
 */
abstract class ModuleWizard extends BaseObject {

	/**
	 * @var string
	 */
	private $_name;

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var Form
	 */
	protected $setup_form;



	/**
	 * @return string
	 */
	public function getName()
	{
		if(!$this->_name) {
			$this->_name = substr(get_called_class(), 23, -7);
		}

		return $this->_name;
	}

	/**
	 * @return string
	 */
	public function getBaseDir()
	{
		return ModuleWizards::getBasePath().$this->getName().'/';
	}

	/**
	 * @return string
	 */
	public function getTrNamespace()
	{
		$ns = get_called_class();

		$ns = str_replace('\\', '.', $ns);

		return $ns;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return Tr::_($this->title, [], $this->getTrNamespace());
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return Tr::_($this->description, [], $this->getTrNamespace());
	}

	/**
	 * @return Form
	 */
	abstract public function generateSetupForm();

	/**
	 * @return Form
	 */
	public function getSetupForm()
	{
		if(!$this->setup_form) {
			$this->setup_form = $this->generateSetupForm();
			$this->setup_form->setCustomTranslatorNamespace( $this->getTrNamespace() );

			$this->setup_form->setAction( Modules::getActionUrl('module_wizard/setup', [ 'wizard'=>$this->getName() ]) );
		}

		return $this->setup_form;
	}

	/**
	 * @return bool
	 */
	public function catchSetupForm()
	{
		$form = $this->getSetupForm();

		if($form->catchInput() && $form->validate()) {
			$form->catchData();

			return true;
		}

		return false;
	}

	/**
	 *
	 * @return Mvc_View
	 */
	public function getView()
	{
		$view = new Mvc_View(  $this->getBaseDir().'views/' );
		$view->setVar('wizard', $this);

		return $view;
	}

	/**
	 *
	 */
	public function handleAction()
	{
		$action = Http_Request::GET()->getString('wizard_action');

		if(
			!$action ||
			strpos($action, '.')!==false
		) {
			return;
		}

		$controller = $this->getBaseDir().'controllers/'.$action.'.php';

		if(!IO_File::exists($controller)) {
			return;
		}

		/** @noinspection PhpIncludeInspection */
		require $controller;
	}


	/**
	 *
	 */
	abstract public function init();

	/**
	 * @return bool
	 */
	abstract public function create();
}