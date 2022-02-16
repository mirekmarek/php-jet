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
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Http_Request;
use Jet\Locale;
use Jet\Tr;
use Jet\UI_messages;
use Jet\SysConf_URI;
use Jet\MVC;

/**
 *
 */
class Bases extends BaseObject implements Application_Part
{
	/**
	 * @var Bases_Base|bool|null
	 */
	protected static Bases_Base|bool|null $__current_base = null;

	/**
	 * @var ?Form
	 */
	protected static ?Form $create_form = null;


	/**
	 * @param string $action
	 * @param array $custom_get_params
	 * @param string|null $custom_base_id
	 *
	 * @return string
	 */
	public static function getActionUrl( string $action, array $custom_get_params = [], ?string $custom_base_id = null ) : string
	{

		$get_params = [];

		if( Bases::getCurrentBaseId() ) {
			$get_params['base'] = Bases::getCurrentBaseId();
		}

		if( $custom_base_id !== null ) {
			$get_params['base'] = $custom_base_id;
			if( !$custom_base_id ) {
				unset( $get_params['base'] );
			}
		}

		if( $action ) {
			$get_params['action'] = $action;
		}

		if( $custom_get_params ) {
			foreach( $custom_get_params as $k => $v ) {
				$get_params[$k] = $v;
			}
		}

		return SysConf_URI::getBase() . 'bases.php?' . http_build_query( $get_params );
	}


	/**
	 * @return Bases_Base[]
	 */
	public static function load(): array
	{
		return MVC::getBases();
	}


	/**
	 * @return Bases_Base[]
	 */
	public static function getBases(): array
	{
		$bases = static::load();

		uasort( $bases, function(
			Bases_Base $a,
			Bases_Base $b
		) {
			return strcmp( $a->getName(), $b->getName() );
		} );

		return $bases;
	}


	/**
	 * @param string $id
	 *
	 * @return null|Bases_Base
	 */
	public static function getBase( string $id ): null|Bases_Base
	{
		$bases = static::load();

		if( !isset( $bases[$id] ) ) {
			return null;
		}

		return $bases[$id];
	}


	/**
	 * @return string|bool
	 */
	public static function getCurrentBaseId(): string|bool
	{
		if( static::getCurrentBase() ) {
			return static::getCurrentBase()->getId();
		}

		return false;
	}


	/**
	 * @return bool|Bases_Base
	 */
	public static function getCurrentBase(): bool|Bases_Base
	{
		if( static::$__current_base === null ) {
			$id = Http_Request::GET()->getString( 'base' );

			static::$__current_base = false;

			if(
				$id &&
				($base = static::getBase( $id ))
			) {
				static::$__current_base = $base;
			}
		}

		return static::$__current_base;
	}


	/**
	 * @return Form
	 */
	public static function getCreateForm(): Form
	{
		if( !static::$create_form ) {

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

				if( Bases::exists( $id ) ) {
					$field->setError('base_id_is_not_unique');

					return false;
				}

				return true;

			} );

			$locales_field = new Form_Field_Hidden( 'locales', '' );
			$locales_field->setDefaultValue( implode( ',', Project::getDefaultLocales( true ) ) );

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

				$bases = Bases::getBases();

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


			$form->setAction( Bases::getActionUrl( 'add' ) );

			static::$create_form = $form;
		}

		return static::$create_form;
	}


	/**
	 * @return bool|Bases_Base
	 */
	public static function catchCreateForm(): bool|Bases_Base
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


		$base = new Bases_Base();
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


	/**
	 * @param string $base_id
	 *
	 * @return bool
	 */
	public static function exists( string $base_id ): bool
	{
		foreach( static::getBases() as $base ) {
			if( $base->getId() == $base_id ) {
				return true;
			}
		}

		return false;
	}
}