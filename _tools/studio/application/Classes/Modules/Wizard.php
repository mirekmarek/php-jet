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
use Jet\IO_Dir;
use Jet\Mvc_View;
use Jet\Tr;
use Jet\Http_Request;

abstract class Modules_Wizard extends BaseObject {
	const WIZARD_NAMESPACE = 'JetStudioModuleWizard';

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
	protected static $wizards_base_path = JET_PATH_BASE.'ModuleWizard/';

	/**
	 * @var Modules_Wizard
	 */
	protected static $__current_wizard;


	/**
	 * @return null|Modules_Wizard
	 */
	public static function getCurrentWizard()
	{
		if(static::$__current_wizard===null) {
			$name = Http_Request::GET()->getString('wizard');

			static::$__current_wizard = false;

			if(
				$name &&
				($module=static::get($name))
			) {
				static::$__current_wizard = $module;
			}
		}

		return static::$__current_wizard;
	}

	/**
	 * @param string $name
	 *
	 * @return Modules_Wizard|null
	 */
	public static function get( $name )
	{
		$list = static::getList();

		if(!isset($list[$name])) {
			return null;
		}

		return $list[$name];
	}


	/**
	 * @return string
	 */
	public static function getWizardsBasePath()
	{
		return static::$wizards_base_path;
	}

	/**
	 * @param string $wizards_base_path
	 */
	public static function setWizardsBasePath( $wizards_base_path )
	{
		static::$wizards_base_path = $wizards_base_path;
	}

	/**
	 * @return Modules_Wizard[]
	 */
	public static function getList()
	{
		$base_path = static::getWizardsBasePath();

		$list = IO_Dir::getList( $base_path, '*.php', false, true );

		$res = [];

		foreach( $list as $path=>$name ) {
			$class_name = Modules_Wizard::WIZARD_NAMESPACE.'\\'.substr( $name, 0, -4 );

			/**
			 * @var Modules_Wizard $wizard
			 */
			$wizard = new $class_name();

			$res[$wizard->getName()] = $wizard;
		}

		return $res;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		$class = get_called_class();
		$name = str_replace('JetStudioModuleWizard\\', '', $class);

		return $name;
	}

	/**
	 * @return string
	 */
	public function getBaseDir()
	{
		return static::getWizardsBasePath().$this->getName().'/';
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
	 * @return string
	 */
	public function renderSetupForm()
	{
		$view = new Mvc_View( $this->getBaseDir() );

		return $view->render('setup_form');
	}

	/**
	 *
	 */
	abstract public function init();

	/**
	 * @return bool
	 */
	abstract public function isReady();

	/**
	 * @return bool
	 */
	abstract public function create();
}