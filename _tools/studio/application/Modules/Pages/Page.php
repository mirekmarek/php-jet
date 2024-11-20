<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Pages;

use Jet\Application_Module_Manifest;
use Jet\Exception;
use Jet\Form_Field_Int;
use Jet\Form_Field_Select;
use Jet\Form_Field_Textarea;
use Jet\Form_Field_Hidden;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\MVC;
use Jet\MVC_Controller;
use Jet\MVC_Layout;
use Jet\MVC_Page;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Checkbox;
use Jet\Factory_MVC;
use Jet\MVC_Page_MetaTag_Interface;
use Jet\SysConf_Jet_MVC;
use Jet\Locale;
use Jet\MVC_Page_Content_Interface;
use Jet\Application_Modules;
use JetStudio\Form_Field_Callable;
use JetStudio\Form_Field_AssocArray;
use JetStudio\Form_Field_MetaTags;
use JetStudio\Form_Field_Array;
use JetStudio\JetStudio;
use JetStudio\ClassParser;
use ReflectionClass;
use ReflectionMethod;

/**
 *
 */
class Page extends MVC_Page
{
	public const MAX_META_TAGS_COUNT = 100;
	public const MAX_HTTP_HEADERS_COUNT = 100;
	
	public const PARAMS_COUNT = 5;

	protected static bool $use_module_pages = false;
	
	protected ?Form $__edit_form_main = null;
	protected ?Form $__edit_form_content = null;
	protected ?Form $__edit_form_static_content = null;
	protected ?Form $__edit_form_callback = null;
	protected ?Form $__delete_content_form = null;
	protected ?Form $__create_content_form = null;

	/**
	 *
	 * @var Page_Content[]
	 */
	protected array $content = [];
	
	protected static ?Form $__create_form = null;
	protected static bool $_translate = false;


	public static function getCreateForm(): Form
	{
		if( !static::$__create_form ) {

			$name_field = new Form_Field_Input( 'name', 'Name:' );
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter page name'
			] );

			$id_field = new Form_Field_Input( 'id', 'Identifier:' );
			$id_field->setIsRequired( true );
			$id_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter page identifier',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid page identifier format',
				'page_id_is_not_unique'                     => 'Page with the identifier already exists',
			] );
			$id_field->setValidator( function( Form_Field_Input $field ) {
				$id = $field->getValue();

				if( !$id ) {
					$field->setError( Form_Field::ERROR_CODE_EMPTY );
					return false;
				}

				if(
					!preg_match( '/^[a-zA-Z0-9\-]{2,}$/i', $id ) ||
					str_contains( $id, '--' )
				) {
					$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

					return false;
				}

				if( Main::exists( $id ) ) {
					$field->setError( 'page_id_is_not_unique' );
					return false;
				}

				return true;

			} );

			$form = new Form(
				'page_create_form',
				[
					$name_field,
					$id_field
				]
			);

			$form->setAction( Main::getActionUrl( 'add' ) );

			static::$__create_form = $form;
		}

		return static::$__create_form;
	}


	public static function catchCreateForm(): bool|Page
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		return static::createPage(
			Main::getCurrentBaseId(),
			Main::getCurrentLocale(),
			$form->field( 'id' )->getValue(),
			$form->field( 'name' )->getValue(),
			Main::getCurrentPage()
		);
	}
	
	public function getEditForm_main(): Form
	{
		if( !$this->__edit_form_main ) {

			$page = $this;

			$name_field = new Form_Field_Input( 'name', 'Name:' );
			$name_field->setDefaultValue( $page->getName() );
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter page name'
			] );
			$name_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				$page->setName( $value );
			} );

			$order_field = new Form_Field_Int( 'order', 'Page order:' );
			$order_field->setDefaultValue( $page->getOrder() );
			$order_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				$page->setOrder( $value );
			} );

			$title_field = new Form_Field_Input( 'title', 'Title:' );
			$title_field->setDefaultValue( $page->getTitle() );
			$title_field->setIsRequired( true );
			$title_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter page title'
			] );
			$title_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				$page->setTitle( $value );
			} );


			$menu_title_field = new Form_Field_Input( 'menu_title', 'Menu item title:' );
			$menu_title_field->setDefaultValue( $page->getMenuTitle() );
			$menu_title_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				$page->setMenuTitle( $value );
			} );


			$breadcrumb_title_field = new Form_Field_Input( 'breadcrumb_title', 'Breadcrumb title:' );
			$breadcrumb_title_field->setDefaultValue( $page->getBreadcrumbTitle() );
			$breadcrumb_title_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				$page->setBreadcrumbTitle( $value );
			} );


			$icon_field = new Form_Field_Input( 'icon', 'Icon:' );
			$icon_field->setDefaultValue( $page->getIcon() );
			$icon_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				$page->setIcon( $value );
			} );


			$is_secret_field = new Form_Field_Checkbox( 'is_secret', 'is secret' );
			$is_secret_field->setDefaultValue( $page->getIsSecret() );
			$is_secret_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				if( !$page->isSecretByDefault() ) {
					$page->setIsSecret( $value );
				}
			} );
			if( $this->isSecretByDefault() ) {
				$is_secret_field->setIsReadonly( true );
				$is_secret_field->setDefaultValue( true );
			}


			$is_active_field = new Form_Field_Checkbox( 'is_active', 'is active' );
			$is_active_field->setDefaultValue( $page->getIsActive() );
			$is_active_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				if( !$page->getIsDeactivatedByDefault() ) {
					$page->setIsActive( $value );
				}
			} );
			if( $this->getIsDeactivatedByDefault() ) {
				$is_active_field->setIsReadonly( true );
				$is_active_field->setDefaultValue( false );
			}

			$SSL_required_field = new Form_Field_Checkbox( 'SSL_required', 'SSL required' );
			$SSL_required_field->setDefaultValue( $page->getSSLRequired() );
			$SSL_required_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				if( !$page->isSSLRequiredByDefault() ) {
					$page->setSSLRequired( $value );
				}
			} );
			if( $this->isSSLRequiredByDefault() ) {
				$SSL_required_field->setIsReadonly( true );
				$SSL_required_field->setDefaultValue( true );
			}

			$fields = [
				$name_field,
				$title_field,
				$menu_title_field,
				$breadcrumb_title_field,
				$icon_field,
				$order_field,

				$is_secret_field,
				$is_active_field,
				$SSL_required_field
			];

			if( $this->getId() != MVC::HOMEPAGE_ID ) {
				$relative_path_fragment_field = new Form_Field_Input( 'relative_path_fragment', 'URL:' );
				$relative_path_fragment_field->setDefaultValue( rawurldecode( $page->getRelativePathFragment() ) );
				$relative_path_fragment_field->setIsRequired( true );
				$relative_path_fragment_field->setFieldValueCatcher( function( $value ) use ( $page, $relative_path_fragment_field ) {
					$value = rawurlencode($value);
					$page->setRelativePathFragment( $value );
				} );
				$relative_path_fragment_field->setIsRequired( true );
				$relative_path_fragment_field->setErrorMessages( [
					Form_Field::ERROR_CODE_EMPTY => 'Please enter URL part',
					'uri_is_not_unique' => 'URL conflicts with page <b>%page%</b>',
				] );
				$relative_path_fragment_field->setValidator( function( Form_Field_Input $field ) use ( $page ) {
					$value = $field->getValue();

					//$value = Data_Text::removeAccents( $value );
					$value = mb_strtolower( $value );

					$value = str_replace( ' ', '-', $value );
					//$value = preg_replace( '/[^a-z0-9-]/i', '', $value );
					$value = preg_replace( '/[^\p{L}0-9\-]/u', '', $value );

					$value = preg_replace( '~([-]{2,})~', '-', $value );

					$field->setValue( $value );


					if( !$value ) {
						$field->setError( Form_Field::ERROR_CODE_EMPTY );
						return false;
					}

					$parent = $page->getParent();
					if( $parent ) {
						foreach( $parent->getChildren() as $ch ) {
							if( $ch->getId() == $page->getId() ) {
								continue;
							}

							if( $ch->getRelativePathFragment() == $value ) {
								$field->setError('uri_is_not_unique', [
									'page' => $ch->getName()
								]);

								return false;
							}
						}
					}

					return true;

				} );


				$fields[] = $relative_path_fragment_field;
			}

			
			$meta_tags = [];
			foreach( $page->getMetaTags() as $meta_tag ) {
				$meta_tags[] = [
					'attribute'=> $meta_tag->getAttribute(),
					'attribute_value' => $meta_tag->getAttributeValue(),
					'content' => $meta_tag->getContent()
				];
			}
			
			$meta_tags_field = new Form_Field_MetaTags('meta_tags', 'Meta Tags:');
			$meta_tags_field->setNewRowsCount( 5 );
			$meta_tags_field->setDefaultValue( $meta_tags );
			$meta_tags_field->setFieldValueCatcher(function($value) {
				
				$meta_tags = [];
				foreach($value as $meta_tag) {
					
					$attribute = $meta_tag['attribute'];
					$attribute_value = $meta_tag['attribute_value'];
					$content = $meta_tag['content'];
					
					$meta_tag = Factory_MVC::getPageMetaTagInstance();
					
					$meta_tag->setAttribute( $attribute );
					$meta_tag->setAttributeValue( $attribute_value );
					$meta_tag->setContent( $content );
					
					$meta_tags[] = $meta_tag;
				}
				
				$this->setMetaTags( $meta_tags );
			});
			
			$fields[] = $meta_tags_field;
			
			
			$http_headers_field = new Form_Field_Array('http_headers', 'HTTP headers:');
			$http_headers_field->setNewRowsCount( 3 );
			$http_headers_field->setDefaultValue( $this->http_headers );
			$http_headers_field->setFieldValueCatcher(function($value) {
				$this->setHttpHeaders( $value );
			});
			$fields[] = $http_headers_field;
			
			
			$params_field = new Form_Field_AssocArray('params', 'Parameters:');
			$params_field->setAssocChar('=');
			$params_field->setNewRowsCount(static::PARAMS_COUNT);
			$params_field->setDefaultValue( $this->parameters );
			$params_field->setFieldValueCatcher(function($value) {
				$this->setParameters( $value );
			});
			$fields[] = $params_field;
			
	
			$base = $this->getBase();
			
			$layout_script_name_field = new Form_Field_Select( 'layout_script_name', 'Layout script name:' );
			$layout_script_name_field->setDefaultValue( $page->getLayoutScriptName() );
			$layout_script_name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value'
			] );
			$layout_script_name_field->setFieldValueCatcher( function( $value ) use ( $page ) {
				$page->setLayoutScriptName( $value );
			} );
			$layouts = $base->getLayoutsList();
			if( !$layouts ) {
				$layouts = ['' => ''];
			}
			
			$layout_script_name_field->setSelectOptions( $layouts );
			
			$fields[] = $layout_script_name_field;
			
			
			$form = new Form(
				'page_edit_form_main',
				$fields
			);

			$form->setAction( Main::getActionUrl( 'edit' ) );

			$this->__edit_form_main = $form;
		}

		return $this->__edit_form_main;
	}
	
	public function catchEditForm_main(): bool
	{
		$form = $this->getEditForm_main();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$form->catchFieldValues();

			return true;
		}

		return false;
	}
	
	
	public function getEditForm_content(): Form
	{
		if( !$this->__edit_form_content ) {


			$fields = [];

			$form = new Form(
				'page_edit_form_content',
				$fields
			);

			$i = 0;
			foreach( $this->content as $content ) {
				$content_form = $content->getEditForm( $this );

				foreach( $content_form->getFields() as $field ) {
					if( !str_starts_with( $field->getName(), '/content/' ) ) {
						if( $field->getName()[0] != '/' ) {
							$field->setName( '/content/' . $i . '/' . $field->getName() );
						} else {
							$field->setName( '/content/' . $i . $field->getName() );
						}
					}


					$form->addField( $field );
				}

				$i++;
			}

			$form->setAction( Main::getActionUrl( 'edit' ) );

			$this->__edit_form_content = $form;

		}

		return $this->__edit_form_content;
	}
	
	public function catchEditForm_content(): bool
	{
		$form = $this->getEditForm_content();

		if( !$form->catchInput() ) {
			return false;
		}

		$i = 0;
		foreach( $this->content as $content ) {

			if( $form->fieldExists( '/content/' . $i . '/module_name' ) ) {
				$selected_module = $form->field( '/content/' . $i . '/module_name' )->getValue();
				$selected_controller = $form->field( '/content/' . $i . '/controller_name' )->getValue();
				
				/**
				 * @var Form_Field_Select $controller_name_field
				 */
				$controller_name_field = $form->field( '/content/' . $i . '/controller_name' );
				$controller_name_field->setSelectOptions( static::getModuleControllers( $selected_module ) );
				
				/**
				 * @var Form_Field_Select $controller_action_field
				 */
				$controller_action_field = $form->field( '/content/' . $i . '/controller_action' );
				$controller_action_field->setSelectOptions( static::getModuleControllerActions( $selected_module, $selected_controller ) );
			}

			$i++;
		}

		if( !$form->validate() ) {
			return false;
		}

		$form->catchFieldValues();

		$this->output = '';
		
		$this->sortContent();

		return true;
	}
	
	public static function createPage( string $base_id,
	                                   Locale $locale,
	                                   string $id,
	                                   string $name,
	                                   ?Page  $parent = null ): Page
	{

		if( !is_object( $locale ) ) {
			$locale = new Locale( $locale );
		}


		$page = new Page();
		$page->setBaseId( $base_id );
		$page->setLocale( $locale );
		$page->setId( $id );
		$page->setName( $name );
		$page->setTitle( $name );

		if( $parent ) {
			$page->setRelativePathFragment( $id );
			$page->parent_id = $parent->getId();
			if( $parent->getRelativePath() ) {
				$page->relative_path = $parent->getRelativePath() . '/' . $page->relative_path_fragment . '/';
			}
			$page->setLayoutScriptName( $parent->getLayoutScriptName() );
			
			$page->setDataFilePath( $parent->getDataDirPath().rawurldecode( $page->getRelativePathFragment() ).'/'.SysConf_Jet_MVC::getPageDataFileName() );
		} else {
			$page->setLayoutScriptName( 'default' );
			
			$page->setDataFilePath( MVC::getBase($base_id)->getPagesDataPath($locale).SysConf_Jet_MVC::getPageDataFileName() );
		}


		return $page;
	}

	
	public function getDeleteContentForm(): Form
	{
		if( !$this->__delete_content_form ) {
			$index_field = new Form_Field_Hidden( 'index' );

			$form = new Form( 'delete_content_form', [$index_field] );

			$form->setAction( Main::getActionUrl( 'content_delete' ) );

			$this->__delete_content_form = $form;
		}

		return $this->__delete_content_form;
	}

	public function catchDeleteContentForm(): Page_Content|null
	{
		$form = $this->getDeleteContentForm();

		$old_content = null;

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$index = (int)$form->getField( 'index' )->getValue();

			if( isset( $this->content[$index] ) ) {
				$old_content = $this->content[$index];
			}

			unset( $this->content[$index] );

			$this->content = array_values( $this->content );

			$this->sortContent();
		}

		return $old_content;
	}


	public function getEditForm_static_content(): Form
	{
		if( !$this->__edit_form_static_content ) {

			$output = $this->getOutput();

			$output_field = new Form_Field_Textarea( 'output', 'Static page content:' );
			$output_field->setDefaultValue( is_string( $output ) ? $output : '' );
			$output_field->setFieldValueCatcher( function( $value ) use ( $output_field ) {
				$value = $output_field->getValueRaw();

				$this->content = [];
				$this->output = $value;
			} );

			$form = new Form(
				'page_edit_form_main',
				[
					$output_field
				]
			);

			$form->setAction( Main::getActionUrl( 'edit' ) );

			$this->__edit_form_static_content = $form;

		}

		return $this->__edit_form_static_content;
	}
	
	public function catchEditForm_static_content(): bool
	{
		$form = $this->getEditForm_static_content();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$form->catchFieldValues();

			return true;
		}

		return false;
	}
	
	public function getEditForm_callback(): Form
	{
		if( !$this->__edit_form_callback ) {

			$output = $this->getOutput();
			
			
			$output_callback = new Form_Field_Callable( 'output_callback', 'Output callback:' );
			$output_callback->setMethodArguments( 'Jet\MVC_Page $page' );
			$output_callback->setMethodReturnType( 'string' );
			$output_callback->setDefaultValue( $output );
			$output_callback->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter callback',
				Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Callback is not callable'
			] );
			$output_callback->setIsRequired(true);
			$output_callback->setFieldValueCatcher( function($value) {
				$this->content = [];
				
				$this->setOutput( $value );
			} );
			
			
			$form = new Form(
				'page_edit_form_callback',
				[
					$output_callback,
				]
			);

			$form->setAction( Main::getActionUrl( 'edit' ) );

			$this->__edit_form_callback = $form;

		}

		return $this->__edit_form_callback;
	}
	
	public function catchEditForm_callback(): bool
	{
		$form = $this->getEditForm_callback();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$form->catchFieldValues();

			return true;
		}

		return false;
	}
	
	public function getContentCreateForm(): Form
	{

		if( !$this->__create_content_form ) {

			$content_kind = new Form_Field_Hidden( 'content_kind' );
			$content_kind->setDefaultValue( Page_Content::CONTENT_KIND_MODULE );


			$is_cacheable = Page_Content::getField__is_cacheable( false );
			$output_position = Page_Content::getField__output_position( MVC_Layout::DEFAULT_OUTPUT_POSITION, $this );
			$output_position_order = Page_Content::getField__output_position_order( 0 );
			
			
			$params_field = new Form_Field_AssocArray('params', 'Parameters:');
			$params_field->setAssocChar('=');
			$params_field->setNewRowsCount(static::PARAMS_COUNT);
			$params_field->setDefaultValue( [] );


			$module_name = Page_Content::getField__module_name( '' );
			$controller_name = Page_Content::getField__controller_name( MVC::MAIN_CONTROLLER_NAME );
			$controller_action = Page_Content::getField__controller_action( 'default' );


			$controller_class = Page_Content::getField__controller_class( '' );
			$controller_class_action = Page_Content::getField__controller_class_action( 'default' );


			$output = Page_Content::getField__output( '' );


			$output_callback = Page_Content::getField__output_callback( '' );

			$fields = [
				$content_kind,

				$is_cacheable,
				$output_position,
				$output_position_order,
				
				$params_field,

				$module_name,
				$controller_name,
				$controller_action,

				$controller_class,
				$controller_class_action,

				$output,

				$output_callback,
			];

			

			$form = new Form( 'create_page_content_form', $fields );

			$form->setAction( Main::getActionUrl( 'content_add' ) );

			$this->__create_content_form = $form;
		}

		return $this->__create_content_form;
	}
	
	public function catchContentCreateForm(): bool|Page_Content
	{
		$form = $this->getContentCreateForm();

		if( !$form->catchInput() ) {
			return false;
		}

		switch( $form->field( 'content_kind' )->getValue() ) {
			case Page_Content::CONTENT_KIND_MODULE:
				$form->field( 'module_name' )->setIsRequired( true );
				$form->field( 'controller_name' )->setIsRequired( true );
				$form->field( 'controller_action' )->setIsRequired( true );

				$selected_module = $form->field( 'module_name' )->getValue();
				$selected_controller = $form->field( 'controller_name' )->getValue();
				
				/**
				 * @var Form_Field_Select $controller_name_field
				 */
				$controller_name_field = $form->field( 'controller_name' );
				$controller_name_field->setSelectOptions( static::getModuleControllers( $selected_module ) );
				
				/**
				 * @var Form_Field_Select $controller_action_field
				 */
				$controller_action_field = $form->field( 'controller_action' );
				$controller_action_field->setSelectOptions( static::getModuleControllerActions( $selected_module, $selected_controller ) );

				break;
			case Page_Content::CONTENT_KIND_CLASS:
				$form->removeField( 'module_name' );
				$form->removeField( 'controller_name' );
				$form->removeField( 'controller_action' );

				$form->field( 'controller_class' )->setIsRequired( true );
				$form->field( 'controller_class_action' )->setIsRequired( true );
				break;
			case Page_Content::CONTENT_KIND_STATIC:
				$form->removeField( 'module_name' );
				$form->removeField( 'controller_name' );
				$form->removeField( 'controller_action' );

				$form->field( 'output' )->setIsRequired( true );
				break;
			case Page_Content::CONTENT_KIND_CALLBACK:
				$form->removeField( 'module_name' );
				$form->removeField( 'controller_name' );
				$form->removeField( 'controller_action' );

				$form->field( 'output_callback' )->setIsRequired( true );
				break;

		}

		if( !$form->validate() ) {
			return false;
		}

		$content = new Page_Content();

		$is_cacheable = $form->field( 'is_cacheable' )->getValue();
		$output_position = $form->field( 'output_position' )->getValue();
		$output_order = $form->field( 'output_position_order' )->getValue();

		if( $output_order < 1 ) {
			$output_order = 0;

			foreach( $this->getContent() as $e_c ) {
				if( $e_c->getOutputPosition() != $output_position ) {
					continue;
				}

				if( $e_c->getOutputPositionOrder() > $output_order ) {
					$output_order = $e_c->getOutputPositionOrder();
				}

			}

			$output_order++;
		}
		
		$params = $form->field( 'params' )->getValue();
		

		$content->setIsCacheable( $is_cacheable );
		$content->setOutputPosition( $output_position );
		$content->setOutputPositionOrder( $output_order );
		$content->setParameters( $params );


		switch( $form->field( 'content_kind' )->getValue() ) {
			case Page_Content::CONTENT_KIND_MODULE:
				$content->setModuleName( $form->field( 'module_name' )->getValue() );
				$content->setControllerName( $form->field( 'controller_name' )->getValue() );
				$content->setControllerAction( $form->field( 'controller_action' )->getValue() );

				break;
			case Page_Content::CONTENT_KIND_CLASS:
				$content->setControllerClass( $form->field( 'controller_class' )->getValue() );
				$content->setControllerAction( $form->field( 'controller_class_action' )->getValue() );
				break;
			case Page_Content::CONTENT_KIND_STATIC:
				$content->setOutput( $form->field( 'output' )->getValue() );
				break;
			case Page_Content::CONTENT_KIND_CALLBACK:
				$content->setOutput( $form->field( 'output_callback' )->getValue() );
				break;
		}

		$this->__create_content_form = null;

		return $content;
	}


	
	public function sortContent(): void
	{
		$i = 0;
		$positions = [];

		foreach( $this->getContent() as $content ) {

			$position = $content->getOutputPosition();

			if( !isset( $positions[$position] ) ) {
				$positions[$position] = [];
			}

			$positions[$position][$i] = $content;

			$i++;
		}

		foreach( $positions as $position => $pd ) {
			uasort(
				$positions[$position],
				function( Page_Content $a, Page_Content $b ) {
					$a_p = $a->getOutputPositionOrder();
					$b_p = $b->getOutputPositionOrder();

					if( $a_p == $b_p ) {
						return 0;
					}

					if( $a_p > $b_p ) {
						return 1;
					}

					return -1;
				}
			);

			$c = 0;
			foreach( $positions[$position] as $content ) {
				/**
				 * @var Page_Content $content
				 */
				$c++;
				$content->setOutputPositionOrder( $c );
			}
		}
	}
	
	public function addContent( MVC_Page_Content_Interface $content ): void
	{

		parent::addContent( $content );
		$this->sortContent();
	}
	
	public function removeContent( int $index ): void
	{
		parent::removeContent( $index );

		$this->sortContent();
	}

	public function save(): bool
	{
		$ok = true;
		try {
			$this->saveDataFile();
		} catch( Exception $e ) {
			$ok = false;
			JetStudio::handleError( $e );
		}

		return $ok;
	}
	
	public function delete(): bool
	{
		$ok = true;
		try {
			IO_Dir::remove( $this->getDataDirPath() );
		} catch( Exception $e ) {
			$ok = false;
			JetStudio::handleError( $e );
		}

		return $ok;
	}


	/**
	 *
	 * @return MVC_Page_MetaTag_Interface[]
	 */
	public function getMetaTags(): array
	{
		$meta_tags = [];
		
		foreach( $this->meta_tags as $mt ) {
			$key = $mt->getAttribute() . ':' . $mt->getAttributeValue();
			if( $key == ':' ) {
				$key = $mt->getContent();
			}
			$meta_tags[$key] = $mt;
		}

		return $meta_tags;
	}
	
	

	public static function getModuleControllerActions( string $module_name, string $controller ): array
	{
		if( Application_Modules::moduleExists( $module_name ) ) {
			$module = Application_Modules::moduleManifest( $module_name );
			$controllers = static::getControllers( $module );
			
			if( isset( $controllers[$controller] ) ) {
				return static::getControllerAction( $module, $controller );
			}
		}
		
		return [];
	}

	public static function getModuleControllers( string $module_name ): array
	{
		if( !Application_Modules::moduleExists( $module_name ) ) {
			return [];
		}
		
		$module = Application_Modules::moduleManifest( $module_name );
		return static::getControllers( $module );
	}
	
	
	protected static function getControllers( Application_Module_Manifest $module ): array
	{
		$controllers = [];
		
		/**
		 * @param string $dir
		 */
		$readDir = function( string $dir ) use ( &$readDir, &$controllers ) {
			$dirs = IO_Dir::getList( $dir, '*', true, false );
			$files = IO_Dir::getList( $dir, '*.php', false, true );
			
			foreach( $files as $path => $name ) {
				$file_data = IO_File::read( $path );
				
				$parser = new ClassParser( $file_data );
				
				foreach( $parser->classes as $class ) {
					$full_name = $parser->namespace->namespace . '\\' . $class->name;
					
					$_class = new ReflectionClass( $full_name );
					
					$parents = [];
					
					while( ($parent = $_class->getParentClass()) ) {
						$parents[] = $parent->getName();
						$_class = $parent;
					}
					
					if( !in_array( MVC_Controller::class, $parents ) ) {
						continue;
					}
					
					$c_n = substr( $class->name, 11 );
					
					$controllers[$c_n] = $c_n;
				}
				
			}
			
			foreach( $dirs as $path => $name ) {
				$readDir( $path );
			}
		};
		
		$readDir( $module->getModuleDir() . 'Controller/' );
		
		return $controllers;
	}
	

	protected static function getControllerAction( Application_Module_Manifest $module, string $controller_name ): array
	{
		$class_name = $module->getNamespace() . 'Controller_' . $controller_name;
		
		$reflection = new ReflectionClass( $class_name );
		
		$methods = $reflection->getMethods( ReflectionMethod::IS_PUBLIC );
		
		$actions = [];
		
		foreach( $methods as $method ) {
			$name = $method->getName();
			if( !str_ends_with( $name, '_Action' ) ) {
				continue;
			}
			
			$name = substr( $name, 0, -7 );
			
			$actions[$name] = $name;
		}
		
		return $actions;
	}
	
}