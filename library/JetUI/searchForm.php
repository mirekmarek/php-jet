<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

use Jet\BaseObject;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Session;
use Jet\Mvc_View;

/**
 * Class searchForm
 * @package JetUI
 */
class searchForm extends BaseObject
{

	/**
	 * @var string
	 */
	protected $search_key = 'search';

	/**
	 * @var string
	 */
	protected static $default_placeholder = 'Search for...';

	/**
	 * @var string
	 */
	protected $placeholder = '';

	/**
	 * @var string
	 */
	protected $name = '';
	/**
	 * @var Session
	 */
	protected $session;

	/**
	 * @return string
	 */
	public static function getDefaultPlaceholder()
	{
		return self::$default_placeholder;
	}

	/**
	 * @param string $default_placeholder
	 */
	public static function setDefaultPlaceholder( $default_placeholder )
	{
		self::$default_placeholder = $default_placeholder;
	}

	/**
	 * @param string $name
	 */
	public function __construct( $name )
	{
		$this->name = $name;
		$this->session = new Session( 'search_form_'.$name );

		$POST = Http_Request::POST();
		if( $POST->exists( $this->getSearchKey() ) ) {
			$this->session->setValue( 'search', $POST->getString( $this->getSearchKey() ) );
			Http_Headers::reload();
		}
	}

	/**
	 * @return string
	 */
	public function getSearchKey()
	{
		return $this->search_key;
	}

	/**
	 * @param string $search_key
	 *
	 * @return $this
	 */
	public function setSearchKey( $search_key )
	{
		$this->search_key = $search_key;

		return $this;
	}



	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->session->getValue( 'search', '' );
	}

	/**
	 * @return string
	 */
	public function getPlaceholder()
	{
		if($this->placeholder) {
			return $this->placeholder;
		}

		return static::getDefaultPlaceholder();
	}

	/**
	 * @param string $placeholder
	 *
	 * @return $this
	 */
	public function setPlaceholder( $placeholder )
	{
		$this->placeholder = $placeholder;

		return $this;
	}


	/**
	 * @return Mvc_View
	 */
	public function getView() {

		$view = UI::getView();
		$view->setVar( 'element', $this );

		return $view;
	}

	/**
	 * @return string
	 */
	public function getAction()
	{
		return Http_Request::getCurrentURI();
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->getView()->render('searchForm');
	}

}