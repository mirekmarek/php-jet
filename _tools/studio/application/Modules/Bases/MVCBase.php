<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\Bases;

use Jet\Exception;
use Jet\IO_Dir;
use Jet\MVC_Base;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Hidden;
use Jet\MVC_Page_Interface;
use Jet\SysConf_Jet_MVC;
use Jet\Tr;
use Jet\Locale;
use Jet\MVC;
use Jet\MVC_Base_LocalizedData_Interface;
use Jet\IO_File;
use Jet\Factory_MVC;

use Jet\UI_messages;
use JetStudio\JetStudio;
use JetStudio\Form_Field_Callable;
use JetStudio\Form_Field_MetaTags;
use JetStudio\Form_Field_AssocArray;

/**
 *
 */
class MVCBase extends MVC_Base
{
	public const PARAMS_COUNT = 5;
	
	/**
	 * @var static[]
	 */
	protected static array $bases = [];
	
	protected static array|null $maps = null;
	
	protected ?Form $__edit_form = null;
	protected ?Form $__add_locale_form = null;
	protected ?Form $__sort_locales_form = null;
	protected static ?Form $__create_form = null;
	protected static string $templates_path;
	
	public static function setTemplatesPath( string $templates_path ): void
	{
		static::$templates_path = $templates_path;
	}
	
	public static function exists( string $base_id ): bool
	{
		foreach( static::_getBases() as $base ) {
			if( $base->getId() == $base_id ) {
				return true;
			}
		}
		
		return false;
	}
	
	
	public static function _createByData( array $data ): static
	{
		$base = new static();
		$base->id = $data['id'];
		unset( $data['id'] );
		
		$base->setData( $data );
		
		return $base;
	}
	
	public function getEditForm(): Form
	{
		if( !$this->__edit_form ) {
			$name_field = new Form_Field_Input( 'name', 'Name:' );
			$name_field->setDefaultValue( $this->getName() );
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter base name'
			] );
			$name_field->setFieldValueCatcher( function( $value ) {
				$this->setName( $value );
			} );
			
			$redirect_to_default_URL = new Form_Field_Checkbox( 'redirect_to_default_URL', 'Redirect to the default URL' );
			$redirect_to_default_URL->setDefaultValue( $this->getRedirectToDefaultURL() );
			$redirect_to_default_URL->setFieldValueCatcher( function( $value ) {
				$this->setRedirectToDefaultURL( $value );
			} );
			
			
			
			$is_secret_field = new Form_Field_Checkbox( 'is_secret', 'is secret' );
			$is_secret_field->setDefaultValue( $this->getIsSecret() );
			$is_secret_field->setFieldValueCatcher( function( $value ) {
				$this->setIsSecret( $value );
			} );
			
			$is_default_field = new Form_Field_Checkbox( 'is_default', 'is default' );
			$is_default_field->setDefaultValue( $this->getIsDefault() );
			$is_default_field->setFieldValueCatcher( function( $value ) {
				$this->setIsDefault( $value );
			} );
			
			$is_active_field = new Form_Field_Checkbox( 'is_active', 'is active' );
			$is_active_field->setDefaultValue( $this->getIsActive() );
			$is_active_field->setFieldValueCatcher( function( $value ) {
				$this->setIsActive( $value );
			} );
			
			$SSL_required_field = new Form_Field_Checkbox( 'SSL_required', 'SSL required' );
			$SSL_required_field->setDefaultValue( $this->getSSLRequired() );
			$SSL_required_field->setFieldValueCatcher( function( $value ) {
				$this->setSSLRequired( $value );
			} );
			
			
			$initializer_field = new Form_Field_Callable( 'initializer', 'Initializer:' );
			$initializer_field->setMethodArguments( 'Jet\MVC_Router $router' );
			$initializer_field->setDefaultValue($this->getInitializer());
			$initializer_field->setErrorMessages([
				Form_Field::ERROR_CODE_EMPTY => 'Please enter initializer',
				Form_Field_Callable::ERROR_CODE_NOT_CALLABLE => 'Initializer is not callable'
			]);
			$initializer_field->setIsRequired(true);
			$initializer_field->setFieldValueCatcher( function($value) {
				$this->setInitializer( $value );
			} );
			
			$fields = [
				$name_field,
				$redirect_to_default_URL,
				$is_default_field,
				$is_secret_field,
				$is_active_field,
				$SSL_required_field,
				$initializer_field
			];
			
			foreach( $this->getLocales() as $locale ) {
				$ld = $this->getLocalizedData( $locale );
				
				
				$ld_is_active_field = new Form_Field_Checkbox( '/' . $locale . '/is_active', 'is active' );
				$ld_is_active_field->setDefaultValue( $ld->getIsActive() );
				$ld_is_active_field->setFieldValueCatcher( function( $value ) use ( $ld ) {
					if( $this->getIsActive() ) {
						$ld->setIsActive( $value );
					}
				} );
				if( !$this->getIsActive() ) {
					$ld_is_active_field->setDefaultValue( false );
					$ld_is_active_field->setIsReadonly( true );
				}
				
				$fields[] = $ld_is_active_field;
				
				
				$ld_SSL_required_field = new Form_Field_Checkbox( '/' . $locale . '/SSL_required', 'SSL required' );
				$ld_SSL_required_field->setDefaultValue( $ld->getSSLRequired() );
				$ld_SSL_required_field->setFieldValueCatcher( function( $value ) use ( $ld ) {
					if( !$this->getSSLRequired() ) {
						$ld->setSSLRequired( $value );
					}
				} );
				if( $this->getSSLRequired() ) {
					$ld_SSL_required_field->setIsReadonly( true );
				}
				$fields[] = $ld_SSL_required_field;
				
				
				$ld_title_field = new Form_Field_Input( '/' . $locale . '/title', 'Title:' );
				$ld_title_field->setDefaultValue( $ld->getTitle() );
				$ld_title_field->setFieldValueCatcher( function( $value ) use ( $ld ) {
					$ld->setTitle( $value );
				} );
				$fields[] = $ld_title_field;
				
				$URL_validate = function( Form_Field_Input $field, $index ) use ( $ld ) {
					$value = $field->getValue();
					if( !$value ) {
						return true;
					}
					
					$value = strtolower( $value );
					$value = str_replace( 'http://', '', $value );
					$value = str_replace( 'https://', '', $value );
					$value = preg_replace( '/[^a-z0-9-.\/:]/i', '', $value );
					$value = preg_replace( '~([/]{2,})~', '/', $value );
					//$value = preg_replace( '~([-]{2,})~', '-', $value );
					$value = preg_replace( '~([.]{2,})~', '.', $value );
					
					$value = trim( $value, '/' );
					$value .= '/';
					
					$field->setValue( $value );
					
					foreach( Main::getBases() as $e_base ) {
						if( $e_base->getId() == $this->getId() ) {
							continue;
						}
						
						foreach( $e_base->getLocales() as $locale ) {
							$e_ld = $e_base->getLocalizedData( $locale );
							
							if( in_array( $value, $e_ld->getURLs() ) ) {
								
								$field->setError(
									'url_is_not_unique',
									[
										'base_name' => $e_base->getName(),
										'locale'    => $locale->getName()
									]
								);
								
								return false;
							}
						}
					}
					
					return true;
				};
				
				$u = 0;
				foreach( $ld->getURLs() as $URL ) {
					$ld_URL_field = new Form_Field_Input( '/' . $locale . '/URLs/' . $u, '' );
					$ld_URL_field->setDefaultValue( $URL );
					$ld_URL_field->setErrorMessages( [
						Form_Field::ERROR_CODE_EMPTY => 'Please enter at least one URL',
						'url_is_not_unique'                         => 'URL conflicts with base <b>%base_name%</b> <b>%locale%</b>',
						'url_is_not_unique_in_self'                 => 'URL conflicts with locale <b>%locale%</b>',
					] );
					
					$ld_URL_field->setValidator( function( Form_Field_Input $field ) use ( $u, &$URL_validate ) {
						return $URL_validate( $field, $u );
					} );
					
					$fields[] = $ld_URL_field;
					
					$u++;
				}
				
				for( $c = 0; $c < 3; $c++ ) {
					$ld_URL_field = new Form_Field_Input( '/' . $locale . '/URLs/' . $u, '' );
					$ld_URL_field->setErrorMessages([
						'url_is_not_unique'                         => 'URL conflicts with base <b>%base_name%</b> <b>%locale%</b>',
						'url_is_not_unique_in_self'                 => 'URL conflicts with locale <b>%locale%</b>',
					]);
					
					$ld_URL_field->setValidator( function( Form_Field_Input $field ) use ( $u, &$URL_validate ) {
						return $URL_validate( $field, $u );
					} );
					
					$fields[] = $ld_URL_field;
					
					$u++;
				}
				
				
				$meta_tags = [];
				foreach( $ld->getDefaultMetaTags() as $meta_tag ) {
					$meta_tags[] = [
						'attribute'=> $meta_tag->getAttribute(),
						'attribute_value' => $meta_tag->getAttributeValue(),
						'content' => $meta_tag->getContent()
					];
				}
				
				$meta_tags_field = new Form_Field_MetaTags( '/' . $locale . '/meta_tags', 'Default Meta Tags:');
				$meta_tags_field->setNewRowsCount( 5 );
				$meta_tags_field->setDefaultValue( $meta_tags );
				$meta_tags_field->setFieldValueCatcher(function($value) use ($ld) {
					
					$meta_tags = [];
					foreach($value as $meta_tag) {
						
						$attribute = $meta_tag['attribute'];
						$attribute_value = $meta_tag['attribute_value'];
						$content = $meta_tag['content'];
						
						$meta_tag = Factory_MVC::getBaseLocalizedMetaTagInstance();
						
						$meta_tag->setAttribute( $attribute );
						$meta_tag->setAttributeValue( $attribute_value );
						$meta_tag->setContent( $content );
						
						$meta_tags[] = $meta_tag;
					}
					
					$ld->setDefaultMetaTags( $meta_tags );
				});
				
				$fields[] = $meta_tags_field;
				
				
				$params_field = new Form_Field_AssocArray('/'.$locale.'/params', 'Parameters:');
				$params_field->setAssocChar('=');
				$params_field->setNewRowsCount(static::PARAMS_COUNT);
				$params_field->setDefaultValue( $ld->getParameters() );
				$params_field->setFieldValueCatcher(function($value) use ($ld) {
					$ld->setParameters( $value );
				});
				$fields[] = $params_field;
			}
			
			$form = new Form(
				'base_edit_form',
				$fields
			);
			
			$form->setAction( Main::getActionUrl( 'edit' ) );
			
			$this->__edit_form = $form;
		}
		
		return $this->__edit_form;
	}
	
	public function catchEditForm(): bool
	{
		$form = $this->getEditForm();
		
		if(
			$form->catchInput() &&
			$form->validate()
		) {
			
			$URLs = [];
			
			$known_URLs = [];
			
			$error = false;
			
			foreach( $this->getLocales() as $locale ) {
				$locale_str = $locale->toString();
				
				$URLs[$locale_str] = [];
				
				for( $u = 0; $u < 100; $u++ ) {
					if( !$form->fieldExists( '/' . $locale_str . '/URLs/' . $u ) ) {
						break;
					}
					
					$field = $form->field( '/' . $locale_str . '/URLs/' . $u );
					
					$URL = $field->getValue();
					
					if( $URL ) {
						$URLs[$locale_str][$u] = $URL;
						
						if( !isset( $known_URLs[$URL] ) ) {
							$known_URLs[$URL] = [
								'locale' => $locale,
								'u'      => $u
							];
						} else {
							$known_URL = $known_URLs[$URL];
							/**
							 * @var Locale $known_URL_locale
							 */
							$known_URL_locale = $known_URL['locale'];
							
							$e_field = $form->field( '/' . $locale . '/URLs/' . $u );
							$e_field->setError( 'url_is_not_unique_in_self', [ 'locale' => $known_URL_locale->getName() ]);
							
							$error = true;
						}
					}
				}
				
				if( !count( $URLs[$locale_str] ) ) {
					$field = $form->field( '/' . $locale_str . '/URLs/0' );
					$field->setError( Form_Field::ERROR_CODE_EMPTY );
					
					$error = true;
				}
			}
			
			if( $error ) {
				return false;
			}
			
			
			$form->catchFieldValues();
			
			$data = $form->getValues();
			
			foreach( $this->getLocales() as $locale ) {
				$locale_str = $locale->toString();
				$ld = $this->getLocalizedData( $locale );
				
				$ld_data = $data[$locale_str];
				
				$URLs = [];
				foreach( $ld_data['URLs'] as $URL ) {
					if(
						$URL &&
						!in_array( $URL, $URLs )
					) {
						$URLs[] = strtolower( $URL );
					}
				}
				$ld->setURLs( $URLs );
			}
			
			return true;
		}
		
		return false;
		
	}
	
	public function getAddLocaleForm(): Form
	{
		if( !$this->__add_locale_form ) {
			$locale_field = new Form_Field_Hidden( 'locale' );
			
			$form = new Form( 'add_locale_form', [$locale_field] );
			$form->setAction( Main::getActionUrl( 'locale_add' ) );
			
			$this->__add_locale_form = $form;
		}
		
		return $this->__add_locale_form;
	}
	
	public function catchAddLocaleForm(): MVC_Base_LocalizedData_Interface|bool
	{
		$form = $this->getAddLocaleForm();
		
		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$locale = $form->getField( 'locale' )->getValue();
			
			if( $locale ) {
				$locale = new Locale( $locale );
				
				return $this->createLocale( $locale );
			}
		}
		
		return false;
	}
	
	public function createLocale( Locale $locale ): bool
	{
		$ok = true;
		try {
			$pages_dir = $this->getPagesDataPath( $locale );
			if( !IO_Dir::exists( $pages_dir ) ) {
				IO_Dir::create( $pages_dir );
			}
			
			
			$base_url = '';
			
			foreach( $this->getLocales() as $default_locale ) {
				$default_ld = $this->getLocalizedData( $default_locale );
				
				foreach( $default_ld->getURLs() as $URL ) {
					$base_url = $URL;
					break;
				}
				
				break;
			}
			
			$base_url = trim( $base_url, '/' );
			if( !$base_url ) {
				$base_url = $this->getId();
			}
			
			$ld = $this->addLocale( $locale );
			$ld->setIsActive( true );
			if( $base_url ) {
				
				if( count( $this->localized_data ) > 1 ) {
					$ld->setURLs( [
						$base_url . '/' . $locale->getLanguage()
					] );
				} else {
					$ld->setURLs( [
						$base_url
					] );
					
				}
			}
			
			$this->save();
			
			$this->createHomePage( $locale );
			
		} catch( Exception $e ) {
			$ok = false;
			JetStudio::handleError( $e );
		}
		
		return $ok;
		
	}
	
	
	public function getSortLocalesForm(): Form
	{
		if( !$this->__sort_locales_form ) {
			$locale_field = new Form_Field_Hidden( 'locales', ''  );
			$locale_field->setDefaultValue( implode( ',', $this->getLocales( true ) ) );
			
			$form = new Form( 'sort_locales_form', [$locale_field] );
			$form->setAction( Main::getActionUrl( 'locale_sort' ) );
			
			$this->__sort_locales_form = $form;
		}
		
		return $this->__sort_locales_form;
	}
	
	public function catchSortLocalesForm(): bool
	{
		$form = $this->getSortLocalesForm();
		
		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$locales = explode( ',', $form->getField( 'locales' )->getValue() );
			
			$this->sortLocales( $locales );
			
			return true;
		}
		
		return false;
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
	
	public function create(): bool
	{
		$ok = true;
		$templates_path = static::$templates_path;
		
		try {
			
			$dir = $this->getBasePath();
			
			if( !IO_Dir::exists( $dir ) ) {
				IO_Dir::create( $dir, false );
			}
			$this->saveDataFile();
			
			static::$maps = null;
			static::$bases = [];
			Main::getBases();
			
			$layouts_dir = $this->getLayoutsPath();
			if( !IO_Dir::exists( $layouts_dir ) ) {
				
				IO_Dir::create( $layouts_dir );
				
			}
			
			if( !IO_File::exists( $layouts_dir . 'default.phtml' ) ) {
				IO_File::copy(
					$templates_path . 'new_base_default_layout.phtml',
					$layouts_dir . 'default.phtml'
				);
			}
			
			foreach( $this->getLocales() as $locale ) {
				
				$pages_dir = $this->getPagesDataPath( $locale );
				if( !IO_Dir::exists( $pages_dir ) ) {
					IO_Dir::create( $pages_dir );
				}
				
				$this->createHomePage( $locale );
			}
			
			
		} catch( Exception $e ) {
			$ok = false;
			JetStudio::handleError( $e, MVCBase::getCreateForm() );
		}
		
		return $ok;
	}
	
	
	
	public static function getDefaultLocales( bool $as_string = false ): array
	{
		$locales = [];
		
		foreach( MVC::getBases() as $base ) {
			foreach( $base->getLocales() as $locale ) {
				$locale_str = (string)$locale;
				
				$locales[$locale_str] = $as_string ? $locale_str : $locale;
			}
		}
		
		return $locales;
	}
	
	
	public static function getCreateForm(): Form
	{
		if( !static::$__create_form ) {
			
			$name_field = new Form_Field_Input( 'name', 'Name:' );
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter base name'
			] );
			
			$id_field = new Form_Field_Input( 'id', 'Identifier:' );
			$id_field->setIsRequired( true );
			$id_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter base identifier',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid identifier format',
				'base_id_is_not_unique'                     => 'Base with the identifier already exists',
			] );
			$id_field->setValidator( function( Form_Field_Input $field ) {
				$id = $field->getValue();
				
				if(
					!preg_match( '/^[a-zA-Z0-9\-]{2,}$/i', $id ) ||
					str_contains( $id, '--' )
				) {
					$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
					
					return false;
				}
				
				if( MVCBase::exists( $id ) ) {
					$field->setError('base_id_is_not_unique');
					
					return false;
				}
				
				return true;
				
			} );
			
			$locales_field = new Form_Field_Hidden( 'locales', '' );
			$locales_field->setDefaultValue( implode( ',', static::getDefaultLocales( true ) ) );
			
			$base_url_field = new Form_Field_Input( 'base_url', 'Base URL:' );
			$base_url_field->setIsRequired( true );
			$base_url_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter base URL',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid URL format',
				'url_is_not_unique'                         => 'URL conflicts with base <b>%base_name%</b> <b>%locale%</b>',
				'url_is_not_unique_in_self'                 => 'URL conflicts with locale <b>%locale%</b>',
			] );
			
			$base_url_field->setValidator( function( Form_Field_Input $field ) {
				$base_url = $field->getValue();
				
				
				if( !preg_match( '/^[a-z0-9\-\/.]{2,}$/i', $base_url ) ) {
					$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
					
					return false;
				}
				
				$bases = Main::getBases();
				
				foreach( $bases as $e_base ) {
					foreach( $e_base->getLocales() as $locale ) {
						$e_ld = $e_base->getLocalizedData( $locale );
						
						if( in_array( $base_url, $e_ld->getURLs() ) ) {
							$field->setError('url_is_not_unique', [
								'base_name' => $e_base->getName(),
								'locale'    => $locale->getName()
							]);
							return false;
						}
					}
				}
				
				return true;
				
			} );
			
			
			$form = new Form(
				'base_create_form',
				[
					$name_field,
					$id_field,
					$base_url_field,
					$locales_field
				]
			);
			
			
			static::$__create_form = $form;
		}
		
		return static::$__create_form;
	}
	
	public static function catchCreateForm(): bool|MVCBase
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}
		
		
		$locales = [];
		
		foreach( explode( ',', $form->field( 'locales' )->getValue() ) as $locale_str ) {
			if( !$locale_str ) {
				continue;
			}
			
			$locale = new Locale( $locale_str );
			
			$locales[$locale_str] = $locale;
		}
		
		if( !$locales ) {
			$form->setCommonMessage(
				UI_messages::createDanger( Tr::_( 'Please select at least one locale' ) )
			);
			return false;
		}
		
		
		$base = new MVCBase();
		$base->setId( $form->field( 'id' )->getValue() );
		$base->setName( $form->field( 'name' )->getValue() );
		$base->setIsActive( true );
		
		$base_url = trim( $form->field( 'base_url' )->getValue(), '/' );
		
		$default_added = false;
		foreach( $locales as $i => $locale ) {
			$ld = $base->addLocale( $locale );
			$ld->setIsActive( true );
			
			if( $default_added ) {
				$ld->setURLs( [
					$base_url . '/' . $locale->getLanguage()
				] );
			} else {
				$default_added = true;
				$ld->setURLs( [
					$base_url
				] );
			}
		}
		
		return $base;
	}
	
	protected function createHomePage( Locale  $locale ): MVC_Page_Interface
	{
		
		$page = Factory_MVC::getPageInstance();
		$page->setBaseId( $this->getId() );
		$page->setLocale( $locale );
		$page->setId( MVC::HOMEPAGE_ID );
		$page->setName( 'Homepage' );
		$page->setTitle( 'Homepage' );
		
		$page->setLayoutScriptName( 'default' );
			
		$page->setDataFilePath( $this->getPagesDataPath( $locale ) . SysConf_Jet_MVC::getPageDataFileName() );
		
		$page->save();
		
		$templates_path = static::$templates_path;
		
		$error_pages_source = $templates_path . 'error_pages/';
		
		if( IO_Dir::exists( $error_pages_source ) ) {
			$error_pages_target = $page->getDataDirPath();
			
			$list = IO_Dir::getList( $error_pages_source, '*.phtml', false, true );
			
			foreach( $list as $path => $name ) {
				IO_File::copy( $path, $error_pages_target . $name );
			}
		}
		
		return $page;
	}
	
}
