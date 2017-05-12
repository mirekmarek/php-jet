<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class tabs_tab
 * @package Jet
 */
class UI_tabs_tab extends UI_BaseElement
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'tabs/tab';


	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var bool
	 */
	protected $is_selected = false;

	/**
	 * @var string
	 */
	protected $get_parameter;

	/**
	 * @var string
	 */
	protected $custom_URL;

	/**
	 *
	 * @param string $id
	 * @param string $title
	 */
	public function __construct( $id, $title )
	{
		$this->id = $id;
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getGetParameter()
	{
		return $this->get_parameter;
	}

	/**
	 * @param string $get_parameter
	 */
	public function setGetParameter( $get_parameter )
	{
		$this->get_parameter = $get_parameter;
	}

	/**
	 * @return bool
	 */
	public function getIsSelected()
	{
		return $this->is_selected;
	}

	/**
	 * @param bool $is_selected
	 */
	public function setIsSelected( $is_selected )
	{
		$this->is_selected = $is_selected;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
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
	 * @return string
	 */
	public function getCustomURL()
	{
		return $this->custom_URL;
	}

	/**
	 * @param string $custom_URL
	 *
	 * @return $this
	 */
	public function setCustomURL( $custom_URL )
	{
		$this->custom_URL = $custom_URL;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		if( $this->custom_URL ) {
			return $this->custom_URL;
		}
		return Http_Request::getCurrentURI( [ $this->getGetParameter() => $this->getId() ] );
	}

}