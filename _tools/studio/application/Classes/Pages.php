<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\Data_Tree;
use Jet\Http_Request;
use Jet\Locale;
use Jet\SysConf_URI;

/**
 *
 */
class Pages extends BaseObject implements Application_Part
{
	/**
	 * @var Bases_Base|bool|null
	 */
	protected static Bases_Base|bool|null $__current_base = null;

	/**
	 * @var Locale|bool|null
	 */
	protected static Locale|bool|null $__current_locale = null;

	/**
	 * @var Pages_Page|bool|null
	 */
	protected static Pages_Page|bool|null $__current_page = null;


	/**
	 * @param string $action
	 * @param array $custom_get_params
	 * @param null|string $custom_page_id
	 * @param null|string $custom_locale
	 * @param null|string $custom_base_id
	 *
	 * @return string
	 */
	public static function getActionUrl( string  $action,
	                                     array   $custom_get_params = [],
	                                     ?string $custom_page_id = null,
	                                     ?string $custom_locale = null,
	                                     ?string $custom_base_id = null ) : string
	{

		$get_params = [];

		if( static::getCurrentBaseId() ) {
			$get_params['base'] = static::getCurrentBaseId();
		}
		if( static::getCurrentLocale() ) {
			$get_params['locale'] = (string)static::getCurrentLocale();
		}
		if( static::getCurrentPageId() ) {
			$get_params['page'] = (string)static::getCurrentPageId();

			$get_params['what'] = static::whatToEdit();
		}

		if( $custom_base_id !== null ) {
			$get_params['base'] = $custom_base_id;
			if( !$custom_base_id ) {
				unset( $get_params['base'] );
			}
		}

		if( $custom_locale !== null ) {
			$get_params['locale'] = $custom_locale;
			if( !$custom_locale ) {
				unset( $get_params['locale'] );
			}
		}

		if( $custom_page_id !== null ) {
			$get_params['page'] = $custom_page_id;
			if( !$custom_page_id ) {
				unset( $get_params['page'] );
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

		return SysConf_URI::getBase() . 'pages.php?' . http_build_query( $get_params );
	}


	/**
	 * @param string $page_id
	 * @param Locale|null $locale
	 * @param string $base_id
	 *
	 * @return null|Pages_Page
	 */
	public static function getPage( string $page_id, Locale|null $locale = null, string $base_id = '' ): null|Pages_Page
	{
		if( !$base_id ) {
			$base_id = static::getCurrentBaseId();
		}

		if( !$locale ) {
			$locale = static::getCurrentLocale();
		}

		return Pages_Page::_get( $page_id, $locale, $base_id );
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
				($base = Bases::getBase( $id ))
			) {
				static::$__current_base = $base;
			}
		}

		return static::$__current_base;
	}


	/**
	 * @return bool|Locale
	 */
	public static function getCurrentLocale(): bool|Locale
	{
		if( static::$__current_locale === null ) {
			$locale = Http_Request::GET()->getString( 'locale' );

			static::$__current_locale = false;

			if(
				$locale &&
				($locale = new Locale( $locale )) &&
				static::getCurrentBase() &&
				static::getCurrentBase()->getHasLocale( $locale )
			) {
				static::$__current_locale = $locale;
			}
		}

		return static::$__current_locale;
	}


	/**
	 * @return string|bool
	 */
	public static function getCurrentPageId(): string|bool
	{
		if( static::getCurrentPage() ) {
			return static::getCurrentPage()->getId();
		}

		return false;
	}

	/**
	 * @return bool|Pages_Page
	 */
	public static function getCurrentPage(): bool|Pages_Page
	{
		if( static::$__current_page === null ) {
			$base_id = static::getCurrentBaseId();
			$locale = static::getCurrentLocale();

			$page_id = Http_Request::GET()->getString( 'page' );

			static::$__current_page = false;

			if(
				$base_id &&
				$locale &&
				$page_id &&
				($page = static::getPage( $page_id, $locale, $base_id ))
			) {
				static::$__current_page = $page;
			}
		}

		return static::$__current_page;
	}

	/**
	 * @return Data_Tree
	 */
	public static function getCurrentPageTree(): Data_Tree
	{

		$tree_data = [];


		$appendNode = function( Pages_Page $page ) use ( &$tree_data, &$appendNode ) {
			$parent = $page->getParent();
			if( $parent ) {
				$tree_data[] = [
					'id' => $page->getId(),
					'parent_id' => $parent->getId(),
					'name' => $page->getName(),
					'module_name' => $page->getSourceModuleName(),
				];
			}


			foreach( $page->getChildren() as $ch ) {
				/**
				 * @var Pages_Page $ch
				 */
				$appendNode( $ch );
			}
		};

		/**
		 * @var Pages_Page $homepage
		 */

		$homepage = static::getCurrentBase()->getHomepage( static::getCurrentLocale() );
		$appendNode( $homepage );

		$tree = new Data_Tree();
		$root = $tree->getRootNode();
		$root->setId( $homepage->getId() );
		$root->setLabel( $homepage->getName() );

		uasort( $tree_data, function( array $a, array $b ) {
			return strcmp( $a['name'], $b['name'] );
		} );

		$tree->setData( $tree_data );


		return $tree;

	}


	/**
	 * @param string $page_id
	 * @param ?Locale $locale = null
	 * @param string $base_id
	 *
	 * @return bool
	 */
	public static function exists( string $page_id, ?Locale $locale = null, string $base_id = '' ): bool
	{

		$page = static::getPage( $page_id, $locale, $base_id );
		if( $page ) {
			return true;
		}


		return false;
	}

	/**
	 * @return string
	 */
	public static function whatToEdit(): string
	{
		if( !static::getCurrentPageId() ) {
			return '';
		}
		return Http_Request::GET()->getString( 'what', 'main', [
			'main',
			'content',
			'static_content',
			'callback'
		] );
	}
}