<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'LocalizedData/Interface.php';
require_once 'LocalizedData/MetaTag.php';

/**
 *
 */
class Mvc_Site_LocalizedData extends BaseObject implements Mvc_Site_LocalizedData_Interface
{

	/**
	 *
	 * @var Mvc_Site_Interface
	 */
	protected $_site = '';

	/**
	 *
	 * @var Locale
	 */
	protected $_locale;

	/**
	 *
	 * @var bool
	 */
	protected $is_active = true;


	/**
	 * @var bool
	 */
	protected $SSL_required = false;

	/**
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @var array
	 */
	protected $URLs = [];

	/**
	 *
	 * @var Mvc_Site_LocalizedData_MetaTag[]
	 */
	protected $default_meta_tags = [];


	/**
	 * @param Locale $locale (optional)
	 */
	public function __construct( Locale $locale = null )
	{

		if( $locale ) {
			$this->setLocale( $locale );
		}

	}

	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite()
	{
		return $this->_site;
	}

	/**
	 * @param Mvc_Site_Interface $_site
	 */
	public function setSite( $_site )
	{
		$this->_site = $_site;
	}

	/**
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->_locale;
	}

	/**
	 * @param Locale $_locale
	 *
	 * @return void
	 */
	protected function setLocale( Locale $_locale )
	{
		$this->_locale = $_locale;
	}

	/**
	 * @return bool
	 */
	public function getIsActive()
	{
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( $is_active )
	{
		$this->is_active = (bool)$is_active;
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title )
	{
		$this->title = $title;
	}

	/**
	 * @return array
	 */
	public function getURLs()
	{
		return $this->URLs;
	}

	/**
	 * @param array $URLs
	 */
	public function setURLs( array $URLs )
	{
		foreach( $URLs as $i=>$URL ) {
			$URL = trim($URL, '/');
			$URL .= '/';

			$URLs[$i] = $URL;
		}

		$this->URLs = $URLs;
	}


	/**
	 * @return string
	 */
	public function getDefaultURL()
	{
		return $this->URLs[0];
	}


	/**
	 * @return bool
	 */
	public function getSSLRequired()
	{
		if($this->_site->getSSLRequired()) {
			return true;
		}

		return $this->SSL_required;
	}

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( $SSL_required )
	{
		$this->SSL_required = $SSL_required;
	}


	/**
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag[]
	 */
	public function getDefaultMetaTags()
	{
		return $this->default_meta_tags;
	}

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface[] $default_meta_tags
	 */
	public function setDefaultMetaTags( $default_meta_tags )
	{
		$this->default_meta_tags = [];

		foreach( $default_meta_tags as $default_meta_tag ) {
			$this->addDefaultMetaTag( $default_meta_tag );
		}
	}

	/**
	 *
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface $default_meta_tag
	 */
	public function addDefaultMetaTag( Mvc_Site_LocalizedData_MetaTag_Interface $default_meta_tag )
	{
		$this->default_meta_tags[] = $default_meta_tag;
	}

	/**
	 *
	 * @param int $index
	 */
	public function removeDefaultMetaTag( $index )
	{
		unset( $this->default_meta_tags[$index] );
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$data = get_object_vars( $this );
		foreach( $data as $k => $v ) {
			if( $k[0]=='_' ) {
				unset( $data[$k] );
			}
		}
		$data['default_meta_tags'] = [];

		foreach( $this->default_meta_tags as $meta_tag ) {
			$data['default_meta_tags'][] = $meta_tag->toArray();
		}


		return $data;
	}
}