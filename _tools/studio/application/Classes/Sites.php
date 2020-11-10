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
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Http_Request;
use Jet\Locale;
use Jet\Mvc_Site_LocalizedData_Interface;
use Jet\Tr;
use Jet\UI_messages;
use Jet\SysConf_URI;

/**
 *
 */
class Sites extends BaseObject implements Application_Part
{
	/**
	 * @var Sites_Site|bool
	 */
	protected static $__current_site;

	/**
	 * @var Form
	 */
	protected static $create_form;


	/**
	 * @return Sites_Site[]
	 */
	public static function load()
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return Sites_Site::loadSites();
	}


	/**
	 * @return Sites_Site[]
	 */
	public static function getSites()
	{
		$sites = static::load();

		uasort( $sites, function(
			Sites_Site $a,
			Sites_Site $b
		) {
			return strcmp( $a->getName(), $b->getName() );
		} );

		return $sites;
	}



	/**
	 * @param string $id
	 *
	 * @return null|Sites_Site
	 */
	public static function getSite( $id )
	{
		$sites = static::load();

		if(!isset( $sites[$id])) {
			return null;
		}

		return $sites[$id];
	}

	/**
	 * @param Sites_Site $site
	 */
	public static function addSite( Sites_Site $site )
	{
		//TODO:
		/*
		static::load();

		static::$sites[$site->getId()] = $site;
		*/
	}



	/**
	 * @return string|bool
	 */
	public static function getCurrentSiteId()
	{
		if(static::getCurrentSite()) {
			return static::getCurrentSite()->getId();
		}

		return false;
	}


	/**
	 * @return null|Sites_Site
	 */
	public static function getCurrentSite()
	{
		if(static::$__current_site===null) {
			$id = Http_Request::GET()->getString('site');

			static::$__current_site = false;

			if(
				$id &&
				($site=static::getSite($id))
			) {
				static::$__current_site = $site;
			}
		}

		return static::$__current_site;
	}


	/**
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {

			$name_field = new Form_Field_Input('name', 'Name:');
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter site name'
			]);

			$id_field = new Form_Field_Input('id', 'Identifier:');
			$id_field->setIsRequired( true );
			$id_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter site identifier',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid identifier format',
			]);
			$id_field->setValidator( function( Form_Field_Input $field ) {
				$id = $field->getValue();

				if(!$id)	{
					$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
					return false;
				}

				if(
					!preg_match('/^[a-zA-Z0-9\-]{2,}$/i', $id) ||
					strpos( $id, '--' )!==false
				) {
					$field->setError(Form_Field_Input::ERROR_CODE_INVALID_FORMAT);

					return false;
				}

				if(
					Sites::exists( $id )
				) {
					$field->setCustomError(
						Tr::_('Site with the identifier already exists'),
						'site_id_is_not_unique'
					);

					return false;
				}

				return true;

			} );

			$locales_field = new Form_Field_Hidden('locales', '', implode(',', Project::getDefaultLocales(true)));

			$form = new Form(
				'site_create_form',
				[
					$name_field,
					$id_field,
					$locales_field
				]
			);

			$form->setAction( Sites::getActionUrl('add') );

			static::$create_form = $form;
		}

		return static::$create_form;
	}


	/**
	 * @return bool|Sites_Site
	 */
	public static function catchCreateForm()
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}


		$locales = [];

		foreach( explode(',', $form->field('locales')->getValue()) as $locale_str ) {
			if(!$locale_str) {
				continue;
			}

			$locale = new Locale($locale_str);

			$locales[$locale_str] = $locale;
		}

		if(!$locales) {
			$form->setCommonMessage(
				UI_messages::createDanger(Tr::_('Please select at least one locale'))
			);
			return false;
		}

		$new_site = static::createSite(
			$form->field('id')->getValue(),
			$form->field('name')->getValue(),
			$locales
		);

		return $new_site;
	}


	/**
	 * @param $action
	 * @param array $custom_get_params
	 * @param string|null $custom_site_id
	 *
	 * @return string $url
	 */
	public static function getActionUrl( $action, array $custom_get_params=[], $custom_site_id=null )
	{

		$get_params = [];

		if(Sites::getCurrentSiteId()) {
			$get_params['site'] = Sites::getCurrentSiteId();
		}

		if($custom_site_id!==null) {
			$get_params['site'] = $custom_site_id;
			if(!$custom_site_id) {
				unset( $get_params['site'] );
			}
		}

		if($action) {
			$get_params['action'] = $action;
		}

		if($custom_get_params) {
			foreach( $custom_get_params as $k=>$v ) {
				$get_params[$k] = $v;
			}
		}

		return SysConf_URI::BASE().'sites.php?'.http_build_query($get_params);
	}

	/**
	 * @param string $site_id
	 *
	 * @return bool
	 */
	public static function exists( $site_id )
	{
		foreach( static::getSites() as $site ) {
			if($site->getId()==$site_id ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * @param string $id
	 * @param string $name
	 * @param Locale[] $locales
	 *
	 * @return Sites_Site
	 */
	public static function createSite( $id, $name, $locales )
	{
		$site = new Sites_Site();
		$site->setId( $id );
		$site->setName( $name );
		$site->setIsActive( true );

		foreach( $locales as $locale ) {
			$site->addLocale( $locale );
		}

		static::addSite( $site );

		return $site;
	}


	/**
	 * @return Form
	 */
	public static function getEditForm()
	{
		return static::getCurrentSite()->getEditForm();
	}

	/**
	 * @return bool
	 */
	public static function catchEditForm()
	{
		return static::getCurrentSite()->catchEditForm();
	}

	/**
	 * @return Form
	 */
	public static function getAddLocaleForm()
	{
		return static::getCurrentSite()->getAddLocaleForm();
	}

	/**
	 * @return Mvc_Site_LocalizedData_Interface|bool
	 */
	public static function catchAddLocaleForm()
	{
		return static::getCurrentSite()->catchAddLocaleForm();
	}




	/**
	 * @return Form
	 */
	public static function getSortLocalesForm()
	{
		return static::getCurrentSite()->getSortLocalesForm();
	}

	/**
	 * @return bool
	 */
	public static function catchSortLocalesForm()
	{
		return static::getCurrentSite()->catchSortLocalesForm();
	}


	/**
	 *
	 * @return Project_Namespace[]
	 */
	public static function getNamespaces()
	{
		return [];
	}


	/**
	 *
	 */
	public static function synchronize()
	{
	}

}