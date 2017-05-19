<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class UI_searchForm extends BaseObject
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'searchForm';

	/**
	 * @var string
	 */
	protected $renderer_script;


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
	public static function getDefaultRendererScript()
	{
		return static::$default_renderer_script;
	}

	/**
	 * @param string $default_renderer_script
	 */
	public static function setDefaultRendererScript( $default_renderer_script )
	{
		static::$default_renderer_script = $default_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultPlaceholder()
	{
		return static::$default_placeholder;
	}

	/**
	 * @param string $default_placeholder
	 */
	public static function setDefaultPlaceholder( $default_placeholder )
	{
		static::$default_placeholder = $default_placeholder;
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
			$this->session->setValue( $this->getSearchKey(), $POST->getString( $this->getSearchKey() ) );
			Http_Headers::reload();
		}
	}


	/**
	 * @return string
	 */
	public function getRendererScript()
	{
		if(!$this->renderer_script) {
			$this->renderer_script = static::getDefaultRendererScript();
		}

		return $this->renderer_script;
	}

	/**
	 * @param string $renderer_script
	 *
	 */
	public function setRendererScript( $renderer_script )
	{
		$this->renderer_script = $renderer_script;
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
		return $this->session->getValue( $this->getSearchKey(), '' );
	}

	/**
	 * @return string
	 */
	public function getPlaceholder()
	{
		if(!$this->placeholder) {
			$this->placeholder = UI::_( static::getDefaultPlaceholder() );
		}

		return $this->placeholder;
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
		return $this->getView()->render($this->getRendererScript());
	}

}