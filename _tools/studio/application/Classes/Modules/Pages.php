<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\MVC;
use Jet\MVC_Layout;
use Jet\SysConf_Jet_Modules;
use Jet\SysConf_Jet_MVC;

/**
 *
 */
class Modules_Pages extends BaseObject
{
	protected Modules_Manifest $module_manifest;

	/**
	 * @var Pages_Page[][]
	 */
	protected array $pages = [];

	/**
	 * @var ?Form
	 */
	protected ?Form $page_create_form = null;

	public function __construct( Modules_Manifest $module_manifest )
	{
		$this->module_manifest = $module_manifest;

		$this->read();
	}


	/**
	 *
	 */
	protected function read(): void
	{
		foreach( MVC::getBases() as $base ) {
			$base_id = $base->getId();
			$locale = $base->getDefaultLocale();

			$root_dir = $this->module_manifest->getModuleDir().SysConf_Jet_Modules::getPagesDir().'/'.$base_id.'/';

			if(!IO_Dir::exists($root_dir)) {
				continue;
			}

			$sub_dirs = IO_Dir::getList($root_dir, get_files: false);
			foreach($sub_dirs as $dir_path=>$dir_name) {

				$page_data_file_path = $dir_path . SysConf_Jet_MVC::getPageDataFileName();

				if(!IO_File::isReadable($page_data_file_path)) {
					continue;
				}

				$page_data = require $page_data_file_path;
				$page_id = $page_data['id'];

				$page_data['relative_path_fragment'] = rawurlencode( basename( $dir_path ) );

				$page = Pages_Page::_createByData( $base, $locale, $page_data );
				$page->setDataFilePath( $page_data_file_path );

				if(!isset($this->pages[$base_id])) {
					$this->pages[$base_id] = [];
				}

				$this->pages[$base_id][$page_id] = $page;
			}
		}
	}



	/**
	 * @return Pages_Page[][]
	 */
	public function getList(): array
	{
		return $this->pages;
	}

	/**
	 * @param string $base_id
	 * @param string $page_id
	 *
	 * @return null|Pages_Page
	 */
	public function getPage( string $base_id, string $page_id ): null|Pages_Page
	{
		if(
			!isset( $this->pages[$base_id] ) ||
			!isset( $this->pages[$base_id][$page_id] )
		) {
			return null;
		}

		$page = $this->pages[$base_id][$page_id];

		$base = Bases::getBase( $page->getBaseId() );
		$page->setLocale( $base->getDefaultLocale() );

		return $page;
	}


	/**
	 * @param string $base_id
	 *
	 * @param Pages_Page $page
	 */
	public function addPage( string $base_id, Pages_Page $page ): void
	{
		if( !isset( $this->pages[$base_id] ) ) {
			$this->pages[$base_id] = [];
		}

		$this->pages[$base_id][$page->getId()] = $page;

		$path = $this->module_manifest->getModuleDir().SysConf_Jet_Modules::getPagesDir().'/'.$base_id.'/'.rawurldecode($page->getRelativePathFragment()).'/'.SysConf_Jet_MVC::getPageDataFileName();
		$page->setDataFilePath( $path );

	}

	/**
	 * @return Form
	 */
	public function getPageCreateForm(): Form
	{
		if( !$this->page_create_form ) {
			$bases = ['' => ''];
			foreach( Bases::getBases() as $base ) {
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

			$form->setAction( Modules::getActionUrl( 'page/add' ) );


			$this->page_create_form = $form;
		}

		return $this->page_create_form;
	}

	/**
	 *
	 * @return Pages_Page|bool
	 */
	public function catchCratePageForm(): Pages_Page|null
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

		$page = new Pages_Page();

		$page->setBaseId( $base_id );
		$page->setId( $page_id );
		$page->setName( $page_name );
		$page->setTitle( $page_name );
		$page->setRelativePathFragment( $page_id );

		$content = new Pages_Page_Content();
		$content->setModuleName( $this->module_manifest->getName() );
		$content->setControllerName( MVC::MAIN_CONTROLLER_NAME );
		$content->setControllerAction( 'default' );
		$content->setOutputPosition( MVC_Layout::DEFAULT_OUTPUT_POSITION );

		$page->setContent( [
			$content
		] );

		$this->addPage( $base_id, $page );

		return $page;
	}

	/**
	 * @param string $name
	 * @param string $base_id
	 * @return string
	 */
	public static function generatePageId( string $name, string $base_id ): string
	{
		$base = Bases::getBase( $base_id );

		return Project::generateIdentifier( $name, function( $id ) use ( $base ) {

			foreach( $base->getLocales() as $locale ) {
				if( Pages::exists( $id, $locale, $base->getId() ) ) {
					return true;
				}
			}

			return false;
		} );
	}


}