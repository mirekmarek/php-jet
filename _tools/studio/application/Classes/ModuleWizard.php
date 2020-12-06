<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Application_Modules;
use Jet\BaseObject;
use Jet\Data_Text;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\Mvc_View;
use Jet\Tr;
use Jet\UI_messages;

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
	 * @var string
	 */
	protected $module_name = '';

	/**
	 * @var array
	 */
	protected $values = [];



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
	 * @param array $fields
	 */
	public function generateSetupForm_mainFields( array &$fields )
	{
		$module_name = new Form_Field_Input('NAME', 'Name:', '' );
		$module_name->setCatcher(function($value) {
			$this->module_name = $value;
		});

		$module_name->setIsRequired(true);
		$module_name->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module name',
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid module name format'
		]);
		$module_name->setValidator( function( Form_Field_Input $field ) {
			$name = $field->getValue();

			return Modules_Manifest::checkModuleName( $field, $name );
		} );

		$fields[] = $module_name;

		$module_label = new Form_Field_Input('LABEL', 'Label:' );
		$module_label->setCatcher(function($value) {
			$this->values['LABEL'] = $value;
		});
		$module_label->setIsRequired(true);
		$module_label->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module label'
		]);
		$fields[] = $module_label;



		$description = new Form_Field_Input('DESCRIPTION', 'Description:' );
		$description->setCatcher(function($value) {
			$this->values['DESCRIPTION']  =$value;
		});
		$fields[] = $description;


		$author = new Form_Field_Input('AUTHOR', 'Author:' );
		$author->setCatcher(function($value) {
			$this->values['AUTHOR'] = $value;
		});
		$fields[] = $author;

		$license = new Form_Field_Input('LICENSE', 'License:' );
		$license->setCatcher(function($value) {
			$this->values['LICENSE'] = $value;
		});
		$fields[] = $license;


		$copyright =new Form_Field_Input('COPYRIGHT', 'Copyright:' );
		$copyright->setCatcher(function($value) {
			$this->values['COPYRIGHT'] = $value;
		});
		$fields[] = $copyright;


	}

	/**
	 * @return Form
	 */
	public function getSetupForm()
	{
		if(!$this->setup_form) {
			$this->setup_form = $this->generateSetupForm();
			$this->setup_form->setCustomTranslatorNamespace( $this->getTrNamespace() );

			$this->setup_form->setAction( ModuleWizards::getActionUrl('create') );
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
	 * @return string
	 */
	public function getModuleNamespace()
	{
		return Application_Modules::getModuleRootNamespace().'\\'.str_replace('.', '\\', $this->module_name);
	}

	/**
	 * @return string
	 */
	public function getModuleTemplateDir()
	{
		return ProjectConf_PATH::TEMPLATES().'module_wizard/'.$this->getName();
	}

	/**
	 * @return bool
	 */
	public function create() {

		$this->values['NAMESPACE'] = $this->getModuleNamespace();

		try {
			$source_dir = $this->getModuleTemplateDir();
			$target_dir = Application_Modules::getBasePath().str_replace('.', DIRECTORY_SEPARATOR, $this->module_name);


			IO_Dir::copy($source_dir, $target_dir);

			$this->create_applyValues( $target_dir );
		} catch( Exception $e ) {
			Application::handleError($e);
			return false;
		}

		UI_messages::success( Tr::_('Module <b>%NAME%</b> has been created', ['NAME'=>$this->module_name], 'module_wizard') );

		return true;
	}

	/**
	 * @param string $dir
	 */
	protected function create_applyValues( $dir )
	{
		$list = IO_Dir::getFilesList($dir);

		foreach($list as $path=>$name) {
			$script = IO_File::read($path);

			$values = [];

			foreach($this->values as $k=>$v) {
				$values['<'.$k.'>'] = $v;
			}

			$script = Data_Text::replaceData( $script, $values );

			IO_File::write($path, $script);
		}

		$list = IO_Dir::getSubdirectoriesList($dir);
		foreach($list as $path=>$name) {
			$this->create_applyValues( $path );
		}

	}

	/**
	 *
	 */
	public function redirectToModuleEditing()
	{
		Http_Headers::movedTemporary('modules.php?module='.urlencode($this->module_name));
		Application::end();
	}
}