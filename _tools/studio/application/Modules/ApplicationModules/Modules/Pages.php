<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModules;

use Jet\BaseObject;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\MVC;
use Jet\MVC_Layout;
use Jet\MVC_Page;
use Jet\MVC_Page_Content;
use Jet\MVC_Page_Interface;
use Jet\SysConf_Jet_Modules;
use Jet\SysConf_Jet_MVC;
use JetStudio\JetStudio;

class Modules_Pages extends BaseObject
{
	protected Modules_Manifest $module_manifest;
	protected array $pages = [];
	protected ?Form $__page_create_form = null;

	public function __construct( Modules_Manifest $module_manifest )
	{
		$this->module_manifest = $module_manifest;
		
		$pages = MVC_Page::getModulePages( $module_manifest );
		
		foreach( $pages as $page ) {
			$this->pages[$page->getBaseId()][$page->getId()] = $page;
		}
	}


	/**
	 * @return MVC_Page_Interface[][]
	 */
	public function getList(): array
	{
		return $this->pages;
	}


	public function getPage( string $base_id, string $page_id ): null|MVC_Page_Interface
	{
		return $this->pages[$base_id][$page_id]??null;
	}

	
	public function addPage( MVC_Page_Interface $page ): bool
	{
		$base_id = $page->getBaseId();
		
		if( !isset( $this->pages[$base_id] ) ) {
			$this->pages[$base_id] = [];
		}

		$this->pages[$base_id][$page->getId()] = $page;
		
		$ok = true;
		try {
			$page->saveDataFile();
		} catch( Exception $e ) {
			$ok = false;
			JetStudio::handleError( $e );
		}
		
		return $ok;
	}

	/**
	 * @return Form
	 */
	public function getPageCreateForm(): Form
	{
		if( !$this->__page_create_form ) {
			$bases = ['' => ''];
			foreach( MVC::getBases() as $base ) {
				$bases[$base->getId()] = $base->getName();
			}

			$base_id = new Form_Field_Select( 'base_id', 'Base: ' );
			$base_id->setSelectOptions( $bases );
			$base_id->setIsRequired( true );
			$base_id->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select base',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select base',
			] );

			$page_name = new Form_Field_Input( 'page_name', 'Page name:' );
			$page_name->setIsRequired( true );
			$page_name->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter page name'
			] );

			$page_id = new Form_Field_Input( 'page_id', 'Page ID:' );
			$page_id->setIsRequired( true );
			$page_id->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter page ID'
			] );


			$form = new Form( 'add_page_form', [
				$base_id,
				$page_name,
				$page_id
			] );

			$form->setAction( Main::getActionUrl( 'page_add' ) );


			$this->__page_create_form = $form;
		}

		return $this->__page_create_form;
	}

	public function catchCratePageForm(): MVC_Page_Interface|bool
	{
		$form = $this->getPageCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$base_id = $form->getField( 'base_id' )->getValue();
		$page_name = $form->getField( 'page_name' )->getValue();
		$page_id = $form->getField( 'page_id' )->getValue();


		$page_id = static::generatePageId( $page_id, $base_id );

		$page = new MVC_Page();

		$page->setBaseId( $base_id );
		$page->setId( $page_id );
		$page->setName( $page_name );
		$page->setTitle( $page_name );
		$page->setRelativePathFragment( $page_id );

		$content = new MVC_Page_Content();
		$content->setModuleName( $this->module_manifest->getName() );
		$content->setControllerName( MVC::MAIN_CONTROLLER_NAME );
		$content->setControllerAction( 'default' );
		$content->setOutputPosition( MVC_Layout::DEFAULT_OUTPUT_POSITION );

		$page->setContent( [
			$content
		] );
		
		$page->setLocale( MVC::getBase($base_id)->getDefaultLocale() );
		
		$path = $this->module_manifest->getModuleDir().SysConf_Jet_Modules::getPagesDir().'/'.$base_id.'/'.$page_name.'/'.SysConf_Jet_MVC::getPageDataFileName();
		
		$page->setDataFilePath( $path );

		return $page;
	}

	public static function generatePageId( string $name, string $base_id ): string
	{
		$base = MVC::getBase( $base_id );

		return JetStudio::generateIdentifier( $name, function( $id ) use ( $base ) {

			foreach( $base->getLocales() as $locale ) {
				if( MVC::getPage( $id, $locale, $base->getId() ) ) {
					return true;
				}
			}

			return false;
		} );
	}


}