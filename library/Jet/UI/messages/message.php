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
class UI_messages_message extends UI_BaseElement
{

	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'messages/message';

	/**
	 * @var string
	 */
	protected $class = '';

	/**
	 * @var string
	 */
	protected $message = '';

	/**
	 * @var string
	 */
	protected $icon = '';

	/**
	 * @var bool
	 */
	protected $closeable = true;


	/**
	 *
	 * @param string $class
	 * @param string $message
	 */
	public function __construct( $class, $message )
	{
		$this->class = $class;
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function setIcon( $icon )
	{
		$this->icon = $icon;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function setClass( $class )
	{
		$this->class = $class;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 *
	 * @return $this
	 */
	public function setMessage( $message )
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getIsCloseable()
	{
		return $this->closeable;
	}

	/**
	 * @param bool $closeable
	 *
	 * @return $this
	 */
	public function setCloseable( $closeable )
	{
		$this->closeable = $closeable;

		return $this;
	}



}