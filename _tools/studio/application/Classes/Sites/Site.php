<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Exception;
use Jet\IO_Dir;
use Jet\Mvc_Layout;
use Jet\Mvc_Site;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Hidden;
use Jet\Tr;
use Jet\Mvc_Factory;
use Jet\Locale;
use Jet\Mvc_Page;
use Jet\Mvc_Site_LocalizedData_Interface;
use Jet\IO_File;

/**
 *
 */
class Sites_Site extends Mvc_Site
{

	/**
	 * @var ?Form
	 */
	protected ?Form $__edit_form = null;


	/**
	 * @var ?Form
	 */
	protected ?Form $__add_locale_form = null;

	/**
	 * @var ?Form
	 */
	protected ?Form $__sort_locales_form = null;


	/**
	 *
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function get( string $id ) : static|null
	{
		return Sites::getSite( $id );
	}

	/**
	 * @return Form
	 */
	public function getEditForm() : Form
	{
		if(!$this->__edit_form) {
			$name_field = new Form_Field_Input('name', 'Name:', $this->getName());
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter site name'
			]);
			$name_field->setCatcher( function( $value ) {
				$this->setName( $value );
			} );


			$is_secret_field = new Form_Field_Checkbox('is_secret', 'is secret', $this->getIsSecret() );
			$is_secret_field->setCatcher( function( $value ) {
				$this->setIsSecret( $value );
			} );

			$is_default_field = new Form_Field_Checkbox('is_default', 'is default', $this->getIsDefault() );
			$is_default_field->setCatcher( function( $value ) {
				$this->setIsDefault( $value );
			} );

			$is_active_field = new Form_Field_Checkbox('is_active', 'is active', $this->getIsActive() );
			$is_active_field->setCatcher( function( $value ) {
				$this->setIsActive( $value );
			} );

			$SSL_required_field = new Form_Field_Checkbox('SSL_required', 'SSL required', $this->getSSLRequired() );
			$SSL_required_field->setCatcher( function( $value ) {
				$this->setSSLRequired( $value );
			} );


			$initializer_class = '';
			$initializer_method = '';
			$initializer = $this->getInitializer();

			if(
				is_array($initializer) &&
				count($initializer)==2
			) {
				$initializer_class = $initializer[0];
				$initializer_method = $initializer[1];
			}


			$initializer_class_field = new Form_Field_Input('initializer_class', 'Initializer:', $initializer_class );
			$initializer_class_method = new Form_Field_Input('initializer_method', '', $initializer_method );

			$fields = [
				$name_field,
				$is_default_field,
				$is_secret_field,
				$is_active_field,
				$SSL_required_field,
				$initializer_class_field,
				$initializer_class_method
			];

			foreach( $this->getLocales() as $locale ) {
				$ld = $this->getLocalizedData( $locale );


				$ld_is_active_field = new Form_Field_Checkbox('/'.$locale.'/is_active', 'is active', $ld->getIsActive() );
				$ld_is_active_field->setCatcher( function( $value ) use ($ld) {
					if($this->getIsActive()) {
						$ld->setIsActive( $value );
					}
				} );
				if(!$this->getIsActive()) {
					$ld_is_active_field->setDefaultValue( false );
					$ld_is_active_field->setIsReadonly( true );
				}

				$fields[] = $ld_is_active_field;


				$ld_SSL_required_field = new Form_Field_Checkbox('/'.$locale.'/SSL_required', 'SSL required', $ld->getSSLRequired() );
				$ld_SSL_required_field->setCatcher( function( $value ) use ($ld) {
					if(!$this->getSSLRequired()) {
						$ld->setSSLRequired( $value );
					}
				} );
				if($this->getSSLRequired()) {
					$ld_SSL_required_field->setIsReadonly(true);
				}
				$fields[] = $ld_SSL_required_field;



				$ld_title_field = new Form_Field_Input('/'.$locale.'/title', 'Title:', $ld->getTitle());
				$ld_title_field->setCatcher( function( $value ) use ($ld) {
					$ld->setTitle( $value );
				} );
				$fields[] = $ld_title_field;

				$URL_validate = function(  Form_Field_Input $field, $index  ) use ($ld) {
					$value = $field->getValue();
					if(!$value) {
						return true;
					}

					$value = strtolower( $value );
					$value = str_replace('http://', '', $value);
					$value = str_replace('https://', '', $value);
					$value = preg_replace( '/[^a-z0-9-.\/]/i', '', $value );
					$value = preg_replace( '~([/]{2,})~', '/', $value );
					//$value = preg_replace( '~([-]{2,})~', '-', $value );
					$value = preg_replace( '~([.]{2,})~', '.', $value );

					$value = trim($value, '/');
					$value .= '/';

					$field->setValue( $value );

					foreach( Sites::getSites() as $e_site ) {
						if($e_site->getId()==$this->getId()) {
							continue;
						}

						foreach( $e_site->getLocales() as $locale ) {
							$e_ld = $e_site->getLocalizedData( $locale );

							if( in_array($value, $e_ld->getURLs()) ) {
								$field->setCustomError(
									Tr::_(
										'URL conflicts with site <b>%site_name%</b> <b>%locale%</b>',
										[
											'site_name' => $e_site->getName(),
											'locale' => $locale->getName()
										]
									),
									'url_is_not_unique'
								);

								return false;
							}
						}
					}

					return true;
				};

				$u = 0;
				foreach( $ld->getURLs() as $URL ) {
					$ld_URL_field = new Form_Field_Input('/'.$locale.'/URLs/'.$u, '', $URL);
					if($u==0) {
						//$ld_URL_field->setIsRequired(true);
						$ld_URL_field->setErrorMessages([
							Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter at least one URL'
						]);
					}

					$ld_URL_field->setValidator( function( Form_Field_Input $field  ) use ($u, &$URL_validate) {
						return $URL_validate($field, $u);
					} );

					$fields[] = $ld_URL_field;

					$u++;
				}

				for( $c=0;$c<3;$c++ ) {
					$ld_URL_field = new Form_Field_Input('/'.$locale.'/URLs/'.$u, '');
					$ld_URL_field->setValidator( function( Form_Field_Input $field  ) use ($u, &$URL_validate) {
						return $URL_validate($field, $u);
					} );

					$fields[] = $ld_URL_field;

					$u++;
				}

				$m = 0;
				foreach( $ld->getDefaultMetaTags() as $meta_tag) {

					$ld_meta_tag_attribute = new Form_Field_Input('/'.$locale.'/meta_tag/'.$m.'/attribute', 'Attribute:', $meta_tag->getAttribute() );
					$fields[] = $ld_meta_tag_attribute;


					$ld_meta_tag_attribute_value = new Form_Field_Input('/'.$locale.'/meta_tag/'.$m.'/attribute_value', 'Attribute value:', $meta_tag->getAttributeValue() );
					$fields[] = $ld_meta_tag_attribute_value;


					$ld_meta_tag_content = new Form_Field_Input('/'.$locale.'/meta_tag/'.$m.'/content', 'Attribute value:', $meta_tag->getContent() );
					$fields[] = $ld_meta_tag_content;

					$m++;
				}

				for( $c=0;$c<5;$c++ ) {

					$ld_meta_tag_attribute = new Form_Field_Input('/'.$locale.'/meta_tag/'.$m.'/attribute', 'Attribute:', '' );
					$fields[] = $ld_meta_tag_attribute;


					$ld_meta_tag_attribute_value = new Form_Field_Input('/'.$locale.'/meta_tag/'.$m.'/attribute_value', 'Attribute value:', '' );
					$fields[] = $ld_meta_tag_attribute_value;



					$ld_meta_tag_content = new Form_Field_Input('/'.$locale.'/meta_tag/'.$m.'/content', 'Attribute value:', '' );
					$fields[] = $ld_meta_tag_content;

					$m++;
				}

			}

			$form = new Form(
				'site_edit_form',
				$fields
			);

			$form->setAction( Sites::getActionUrl('edit') );

			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm() : bool
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

				for( $u=0;$u<100;$u++ ) {
					if( !$form->fieldExists( '/' . $locale_str . '/URLs/' . $u ) ) {
						break;
					}

					$field = $form->field( '/' . $locale_str . '/URLs/' . $u );

					$URL = $field->getValue();

					if($URL) {
						$URLs[$locale_str][$u] = $URL;

						if(!isset($known_URLs[$URL])) {
							$known_URLs[$URL] = [
								'locale' => $locale,
								'u' => $u
							];
						} else {
							$known_URL = $known_URLs[$URL];
							/**
							 * @var Locale $known_URL_locale
							 */
							$known_URL_locale = $known_URL['locale'];

							$e_field = $form->field('/'.$locale.'/URLs/'.$u);
							$e_field->setCustomError(
								Tr::_(
									'URL conflicts with locale <b>%locale%</b>',
									[
										'locale' => $known_URL_locale->getName()
									]
								),
								'url_is_not_unique'
							);

							$error = true;
						}
					}
				}

				if(!count($URLs[$locale_str])) {
					$field = $form->field( '/' . $locale_str . '/URLs/0' );
					$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );

					$error = true;
				}
			}

			if($error) {
				return false;
			}


			$form->catchData();

			$initializer_class = $form->getField('initializer_class')->getValue();
			$initializer_method = $form->getField('initializer_method')->getValue();

			if(
				$initializer_class &&
				$initializer_method
			) {
				$this->setInitializer([ $initializer_class, $initializer_method ]);
			}



			$data = $form->getData()->getRawData();

			foreach( $this->getLocales() as $locale ) {
				$locale_str = $locale->toString();
				$ld = $this->getLocalizedData($locale);

				$ld_data = $data[$locale_str];

				$URLs = [];
				foreach( $ld_data['URLs'] as $URL ) {
					if(
						$URL &&
						!in_array($URL, $URLs)
					) {
						$URLs[] = strtolower($URL);
					}
				}
				$ld->setURLs( $URLs );


				$meta_tags = [];
				foreach( $ld_data['meta_tag'] as $mt_d ) {
					$attribute = $mt_d['attribute'];
					$attribute_value = $mt_d['attribute_value'];
					$content = $mt_d['content'];

					if(
						!$attribute && !$attribute_value && !$content
					) {
						continue;
					}

					$meta_tag = Mvc_Factory::getSiteLocalizedMetaTagInstance();

					$meta_tag->setAttribute( $attribute );
					$meta_tag->setAttributeValue( $attribute_value );
					$meta_tag->setContent( $content );

					$meta_tags[] = $meta_tag;
				}


				$ld->setDefaultMetaTags( $meta_tags );
			}

			return true;
		}

		return false;

	}


	/**
	 * @return Form
	 */
	public function getAddLocaleForm() : Form
	{
		if(!$this->__add_locale_form) {
			$locale_field = new Form_Field_Hidden('locale');

			$form = new Form('add_locale_form', [$locale_field]);
			$form->setAction( Sites::getActionUrl('locale/add') );

			$this->__add_locale_form = $form;
		}

		return $this->__add_locale_form;
	}

	/**
	 * @return Mvc_Site_LocalizedData_Interface|bool
	 */
	public function catchAddLocaleForm() : Mvc_Site_LocalizedData_Interface|bool
	{
		$form = $this->getAddLocaleForm();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$locale = $form->getField('locale')->getValue();

			if($locale) {
				$locale = new Locale($locale);

				return $this->createLocale( $locale );
			}
		}

		return false;
	}


	/**
	 * @param Locale $locale
	 *
	 * @return bool
	 */
	public function createLocale( Locale $locale ) : bool
	{
		$ok = true;
		try {
			$pages_dir = $this->getPagesDataPath( $locale );
			if(!IO_Dir::exists($pages_dir)) {
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

			$base_url = trim($base_url, '/');
			if(!$base_url) {
				$base_url = $this->getId();
			}

			$ld = $this->addLocale( $locale );
			$ld->setIsActive( true );
			if($base_url) {

				if(count($this->localized_data)>1) {
					$ld->setURLs([
						$base_url.'/'.$locale->getLanguage()
					]);
				} else {
					$ld->setURLs([
						$base_url
					]);

				}
			}

			$this->save();

			$homepage = Pages_Page::createPage(
				$this->getId(),
				$locale,
				Mvc_Page::HOMEPAGE_ID,
				'Homepage'
			);

			$homepage->save();

			$this->create_applyTemplate_errorPages( $homepage );

		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;

	}


	/**
	 * @return Form
	 */
	public function getSortLocalesForm() : Form
	{
		if(!$this->__sort_locales_form) {
			$locale_field = new Form_Field_Hidden('locales', '', implode(',', $this->getLocales(true)));

			$form = new Form('sort_locales_form', [$locale_field]);
			$form->setAction( Sites::getActionUrl('locale/sort') );

			$this->__sort_locales_form = $form;
		}

		return $this->__sort_locales_form;
	}

	/**
	 * @return bool
	 */
	public function catchSortLocalesForm() : bool
	{
		$form = $this->getSortLocalesForm();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$locales = explode(',', $form->getField('locales')->getValue());

			$this->sortLocales( $locales );

			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function save() : bool
	{
		$ok = true;
		try {
			$this->saveDataFile();
		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}


	/**
	 *
	 */
	public function create() : bool
	{
		$ok = true;
		$templates_path = ProjectConf_PATH::TEMPLATES().'/';

		try {
			$dir = $this->getBasePath();

			if(!IO_Dir::exists($dir)) {
				IO_Dir::create( $dir, false );
			}
			$this->saveDataFile();

			static::$sites = null;
			Sites::load();

			$layouts_dir = $this->getLayoutsPath();
			if(!IO_Dir::exists($layouts_dir)) {

				IO_Dir::create( $layouts_dir );

			}

			if(!IO_File::exists($layouts_dir.'default.phtml')) {
				IO_File::copy(
					$templates_path.'new_site_default_layout.phtml',
					$layouts_dir.'default.phtml'
				);
			}

			foreach( $this->getLocales() as $locale ) {

				$pages_dir = $this->getPagesDataPath( $locale );
				if(!IO_Dir::exists($pages_dir)) {
					IO_Dir::create( $pages_dir );
				}

				$homepage = Pages_Page::createPage(
					$this->id,
					$locale,
					Pages_Page::HOMEPAGE_ID,
					'Homepage'
				);

				$homepage->save();

				$this->create_applyTemplate_errorPages( $homepage );
			}


		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

	/**
	 * @param Pages_Page $homepage
	 */
	public function create_applyTemplate_errorPages( Pages_Page $homepage ) : void
	{
		$templates_path = ProjectConf_PATH::TEMPLATES().'/';

		$error_pages_source = $templates_path.'error_pages/';

		if( IO_Dir::exists($error_pages_source) ) {
			$error_pages_target = $homepage->getDataDirPath();

			$list = IO_Dir::getList($error_pages_source, '*.phtml', false, true);

			foreach( $list as $path=>$name ) {
				IO_File::copy( $path, $error_pages_target.$name );
			}
		}

	}

	/**
	 * @return array
	 */
	public function getLayoutsList() : array
	{
		$list = IO_Dir::getList( $this->getLayoutsPath(), '*.'.Mvc_Layout::getScriptFileSuffix(), false, true );

		$res = [];

		$len = strlen(Mvc_Layout::getScriptFileSuffix())+1;
		$len *= -1;

		foreach( $list as $name ) {
			$name = substr($name, 0, $len);

			$res[$name] = $name;
		}

		return $res;
	}

	/**
	 * @param string $layout_script_name
	 *
	 * @return array
	 */
	public function getLayoutOutputPositions( string $layout_script_name ) : array
	{
		$res = [
			Mvc_Layout::DEFAULT_OUTPUT_POSITION => Tr::_('Main position')
		];

		$layout_file_path = $this->getLayoutsPath().$layout_script_name.'.'.Mvc_Layout::getScriptFileSuffix();

		if( IO_File::isReadable($layout_file_path) ) {
			$layout = IO_File::read( $layout_file_path );

			if( preg_match_all(
				'/<'.Mvc_Layout::TAG_POSITION. '[ ]+name="([a-zA-Z0-9\-_ ]*)"[^\/]*\/>/i',
				$layout,
				$matches
			) ) {

				foreach( $matches[1] as $p ) {
					$res[$p] = $p;
				}
			}

		}

		return $res;
	}


}
