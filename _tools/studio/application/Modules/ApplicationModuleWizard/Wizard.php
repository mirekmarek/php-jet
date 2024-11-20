<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModuleWizard;

use Jet\Application_Modules;
use Jet\BaseObject;
use Jet\Data_Text;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Http_Headers;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\MVC_View;
use Jet\SysConf_Jet_Modules;
use Jet\SysConf_Jet_MVC;
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\UI_messages;
use JetStudio\JetStudio;

/**
 *
 */
abstract class Wizard extends BaseObject
{


	private ?string $_name = null;
	protected string $title = '';
	protected string $description = '';
	protected ?Form $__setup_form = null;
	protected string $module_name = '';
	protected array $values = [];


	public function getName(): string
	{
		if( !$this->_name ) {
			$ns = explode('\\', static::class );

			$this->_name = $ns[2];
		}

		return $this->_name;
	}

	public function getBaseDir(): string
	{
		return ModuleWizards::getBasePath() . $this->getName() . '/';
	}

	public function getTrNamespace(): string
	{
		return 'Module.ApplicationModuleWizard.'.$this->getName();
	}

	public function getTitle(): string
	{
		return Tr::_( $this->title, [], $this->getTrNamespace() );
	}

	public function getDescription(): string
	{
		return Tr::_( $this->description, [], $this->getTrNamespace() );
	}
	
	abstract public function generateSetupForm(): Form;
	
	
	public function generateSetupForm_mainFields( array &$fields ): void
	{
		$module_name = new Form_Field_Input( 'NAME', 'Name:' );
		$module_name->setFieldValueCatcher( function( $value ) {
			$this->module_name = $value;
		} );

		$module_name->setIsRequired( true );
		$module_name->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please enter module name',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid module name format',
			'module_name_is_not_unique' => 'Module with the same name already exists',
		] );
		$module_name->setValidator( function( Form_Field_Input $field ) {
			$name = $field->getValue();
			
			if(
				!preg_match( '/^[a-z0-9.]{3,}$/i', $name ) ||
				str_contains( $name, '..' ) ||
				$name[0] == '.' ||
				$name[strlen( $name ) - 1] == '.'
			) {
				$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
				return false;
			}
			
			if( Application_Modules::moduleExists( $name ) ) {
				$field->setError('module_name_is_not_unique');
				return false;
			}
			
			return true;

		} );

		$fields[] = $module_name;

		$module_label = new Form_Field_Input( 'LABEL', 'Label:' );
		$module_label->setFieldValueCatcher( function( $value ) {
			$this->values['LABEL'] = $value;
		} );
		$module_label->setIsRequired( true );
		$module_label->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter module label'
		] );
		$fields[] = $module_label;


		$description = new Form_Field_Input( 'DESCRIPTION', 'Description:' );
		$description->setFieldValueCatcher( function( $value ) {
			$this->values['DESCRIPTION'] = $value;
		} );
		$fields[] = $description;


		$author = new Form_Field_Input( 'AUTHOR', 'Author:' );
		$author->setFieldValueCatcher( function( $value ) {
			$this->values['AUTHOR'] = $value;
		} );
		$fields[] = $author;

		$license = new Form_Field_Input( 'LICENSE', 'License:' );
		$license->setFieldValueCatcher( function( $value ) {
			$this->values['LICENSE'] = $value;
		} );
		$fields[] = $license;


		$copyright = new Form_Field_Input( 'COPYRIGHT', 'Copyright:' );
		$copyright->setFieldValueCatcher( function( $value ) {
			$this->values['COPYRIGHT'] = $value;
		} );
		$fields[] = $copyright;


	}
	
	public function getSetupForm(): Form
	{
		if( !$this->__setup_form ) {
			$this->__setup_form = $this->generateSetupForm();
			$this->__setup_form->setCustomTranslatorDictionary( $this->getTrNamespace() );

			$this->__setup_form->setAction( ModuleWizards::getActionUrl( 'create' ) );
		}

		return $this->__setup_form;
	}
	
	public function catchSetupForm(): bool
	{
		$form = $this->getSetupForm();

		if( $form->catchInput() && $form->validate() ) {
			$form->catchFieldValues();

			return true;
		}

		return false;
	}
	
	public function getView(): MVC_View
	{
		$view = new MVC_View( $this->getBaseDir() . 'views/' );
		$view->setVar( 'wizard', $this );

		return $view;
	}

	public function handle(): string
	{
		return Tr::setCurrentDictionaryTemporary( $this->getTrNamespace(), function() {
			$this->init();
			
			$class_name = ModuleWizards::WIZARD_NAMESPACE . '\\' . $this->_name . '\\Controller';
			
			/**
			 * @var Wizard_Controller $controller
			 */
			$controller = new $class_name( $this->getView(), $this );
			return $controller->handle();
		} );
	}
	
	abstract public function init(): void;
	
	public function getModuleNamespace(): string
	{
		return SysConf_Jet_Modules::getModuleRootNamespace() . '\\' . str_replace( '.', '\\', $this->module_name );
	}

	public function getModuleTemplateDir(): string
	{
		return $this->getBaseDir().'/template/';
	}
	
	public function getTargetDir() : string
	{
		return SysConf_Path::getModules() . str_replace( '.', DIRECTORY_SEPARATOR, $this->module_name );
	}
	
	public function create(): bool
	{

		$this->values['NAMESPACE'] = $this->getModuleNamespace();

		try {
			$source_dir = $this->getModuleTemplateDir();
			$target_dir = $this->getTargetDir();


			IO_Dir::copy( $source_dir, $target_dir );

			$this->create_applyValues( $target_dir );
			$this->create_page( $target_dir );
			$this->create_menuItem( $target_dir );
			$this->create_generateFiles( $target_dir );

		} catch( Exception $e ) {
			JetStudio::handleError( $e );
			return false;
		}

		UI_messages::success( Tr::_( 'Module <b>%NAME%</b> has been created',
			['NAME' => $this->module_name],
			'Module.ApplicationModuleWizard'
		) );

		return true;
	}

	public function create_page( string $target_dir ) : void
	{
		if(
			!empty($this->values['PAGE_BASE_ID']) &&
			!empty($this->values['PAGE_ID']) &&
			!empty($this->values['PAGE_PATH_FRAGMENT'])
		) {
			$pages_dir = $target_dir.'/'.SysConf_Jet_Modules::getPagesDir().'/'.$this->values['PAGE_BASE_ID'].'/';

			IO_Dir::create($pages_dir);
			$pages_dir .= rawurldecode($this->values['PAGE_PATH_FRAGMENT']).'/';
			IO_Dir::create($pages_dir);

			IO_File::writeDataAsPhp($pages_dir.SysConf_Jet_MVC::getPageDataFileName(), [
				'id'       => $this->values['PAGE_ID'],
				'title'    => $this->values['PAGE_TITLE'],
				'icon'     => $this->values['PAGE_ICON'],
				'contents' => [
					[
						'controller_action' => 'default'
					]
				],
			]);
		}

	}

	public function create_menuItem( string $target_dir ) : void
	{
		if(
			!empty($this->values['TARGET_MENU_SET_ID']) &&
			!empty($this->values['TARGET_MENU_ID']) &&
			!empty($this->values['MENU_ITEM_ID'])
		) {
			$menus_dir = $target_dir.'/'.SysConf_Jet_Modules::getMenuItemsDir().'/';

			IO_Dir::create($menus_dir);
			IO_File::writeDataAsPhp($menus_dir.$this->values['TARGET_MENU_SET_ID'].'.php', [
				$this->values['TARGET_MENU_ID'] => [
					$this->values['MENU_ITEM_ID'] => [
						'separator_before' => true,
						'page_id'          => $this->values['PAGE_ID'],
						'index'            => 200,
					],
				],
			]);


		}
	}
	
	public function create_generateFiles( string $target_dir ) : void
	{
	
	}
	
	protected function create_applyValues( string $dir ): void
	{
		$list = IO_Dir::getFilesList( $dir );
		
		$values = [];
		
		foreach( $this->values as $k => $v ) {
			$values['<' . $k . '>'] = $v;
		}
		
		$rename = [];
		
		foreach( $list as $path => $name ) {
			$script = IO_File::read( $path );
			
			$script = Data_Text::replaceData( $script, $values );

			IO_File::write( $path, $script );
			
			if(str_contains($name, '%<')) {
				$rename[] = $path;
			}
		}
		
		foreach($rename as $old_path ) {
			$new_path = Data_Text::replaceData( $old_path, $values );
			
			IO_File::rename( $old_path, $new_path );
		}
		
		

		$list = IO_Dir::getSubdirectoriesList( $dir );
		foreach( $list as $path => $name ) {
			$this->create_applyValues( $path );
		}

	}

	public function redirectToModuleEditing(): void
	{
		Http_Headers::movedTemporary( JetStudio::getModule_ApplicationModules()->getEditModuleURL( $this->module_name ) );
		JetStudio::end();
	}
}