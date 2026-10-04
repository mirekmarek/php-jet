<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Error;
use Exception;
use Jet\AJAX;
use Jet\Application_Modules;
use Jet\DataModel;
use Jet\DataModel_Helper;
use Jet\Http_Request;
use Jet\IO_Dir;
use Jet\Locale;
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\Translator;
use Jet\UI_messages;
use ReflectionClass;


/**
 *
 */
class Installer_Step_Install_Controller extends Installer_Step_Controller
{
	protected string $icon = 'gears';
	
	protected string $label = 'Install';
	
	protected string $error_message = '';
	
	public function main(): void
	{
		$this->catchContinue();
		
		$steps = [
			'createDb' => Tr::_('Create database'),
			'modules'  => Tr::_('Install modules'),
			'bases'    => Tr::_('Create bases'),
		];
		
		$installation_step = Http_Request::GET()->getString('is', default_value: '', valid_values: array_keys( $steps ));
		
		if($installation_step) {
			$method = 'install_'.$installation_step;
			
			$res = $this->{$method}();
			if($res) {
				AJAX::commonResponse([
					'ok' => true
				]);
			} else {
				AJAX::commonResponse([
					'ok' => false,
					'error' => $this->error_message
				]);
				
			}
		}
		
		$this->view->setVar('steps', $steps);
		
		
		$this->render('default');
	}
	
	public function install_createDb() : bool
	{
		$classes = [];
		
		$finder = new class {
			/**
			 * @var array<string>
			 */
			protected array $classes = [];
			protected string $dir = '';
			
			public function __construct()
			{
				$this->dir = SysConf_Path::getApplication() . 'Classes/';
				$this->readDir( $this->dir );
				
				asort( $this->classes );
			}
			
			protected function readDir( string $dir ): void
			{
				$dirs = IO_Dir::getList( $dir, '*', true, false );
				$files = IO_Dir::getList( $dir, '*.php', false, true );
				
				foreach( $files as $path => $name ) {
					$class = str_replace($this->dir, '', $path);
					$class = str_replace('.php', '', $class);
					
					$class = str_replace('/', '_', $class);
					$class = str_replace('\\', '_', $class);
					
					$class = '\\JetApplication\\'.$class;
					
					/** @phpstan-ignore argument.type */
					$reflection = new ReflectionClass( $class );
					
					if(
						$reflection->isSubclassOf( DataModel::class ) &&
						!$reflection->isAbstract()
					) {
						$this->classes[] = $reflection->getName();
					}
				}
				
				foreach( $dirs as $path => $name ) {
					$this->readDir( $path );
				}
			}
			
			/**
			 * @return array<string>
			 */
			public function getClasses(): array
			{
				return $this->classes;
			}
		};
		
		$classes = $finder->getClasses();
		
		$result = [];
		$OK = true;
		
		foreach( $classes as $class ) {
			$result[$class] = true;
			try {
				DataModel_Helper::create( $class );
			} catch( Error|Exception $e ) {
				$result[$class] = $e->getMessage();
				$OK = false;
			}
			
		}
		
		
		if(!$OK) {
			$this->view->setVar( 'result', $result );
			$this->view->setVar( 'OK', false );
			
			$this->error_message = $this->view->render( 'create-db' );
		}
		
		return $OK;
	}
	
	public function install_modules() : bool
	{
		$all_modules = Application_Modules::allModulesList();
		//$modules_scope = [];
		$selected_modules = [];
		foreach($all_modules as $module) {
			//$modules_scope[$module->getName()] = $module->getLabel();
			$selected_modules[] = $module->getName();
		}
		
		
		$this->view->setVar( 'modules', $all_modules );
		
		
		$this->catchContinue();
		
		
		
		
		$result = [];
		
		$OK = true;
		
		$tr_dir = SysConf_Path::getDictionaries();
		/** @phpstan-ignore constant.notFound */
		SysConf_Path::setDictionaries(__APP_DICTIONARIES__);
		
		foreach( $selected_modules as $module_name ) {
			$result[$module_name] = true;
			
			if( $all_modules[$module_name]->isActivated() ) {
				continue;
			}
			
			try {
				Application_Modules::installModule( $module_name );
				/** @phpstan-ignore catch.neverThrown */
			} catch( Error|Exception $e ) {
				$result[$module_name] = $e->getMessage();
				
				$OK = false;
			}
			
			if( $result[$module_name] !== true ) {
				continue;
			}
			
			try {
				Application_Modules::activateModule( $module_name );
				/** @phpstan-ignore catch.neverThrown */
			} catch( Error|Exception $e ) {
				$result[$module_name] = $e->getMessage();
				$OK = false;
			}
			
		}
		
		SysConf_Path::setDictionaries( $tr_dir );
		

		if(!$OK) {
			$this->view->setVar( 'result', $result );
			$this->view->setVar( 'OK', false );
			
			$this->error_message = $this->view->render( 'modules-installation-result' );
		}
		
		return true;
		
		
	}
	
	public function install_bases() : bool
	{
		$bases = Installer::getBases();
		
		$templates_dir = dirname(__DIR__, 2).'/base-templates/';
		
		try {
			foreach( $bases as $base ) {
				$base->saveDataFile();
				
				$pages_template_base_dir = $templates_dir.$base->getId().'/pages/';
				
				foreach( $base->getLocales() as $locale ) {
					/**
					 * @var Locale $locale
					 */
					$pages_template_dir = $pages_template_base_dir.$locale;
					if(!IO_Dir::exists($pages_template_dir)) {
						$pages_template_dir = $pages_template_base_dir.'default';
					}
					
					$pages_target_dir = $base->getPagesDataPath( $locale );
					
					IO_Dir::copy( $pages_template_dir, $pages_target_dir );
					
				}
			}
			
		} catch( Error|Exception $e ) {
			$this->error_message = UI_messages::createDanger(
				Tr::_( 'Something went wrong: %error%', ['error' => $e->getMessage()], Translator::COMMON_DICTIONARY )
			);
			
			return false;
		}
		
		return true;
	}
	
}