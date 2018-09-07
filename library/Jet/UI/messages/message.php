<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
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
	protected $context = '';

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
	 * @param string $context
	 */
	public function __construct( $class, $message, $context='' )
	{
		$this->class = $class;
		$this->message = $message;
		$this->context = $context;
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
	 * @return string
	 */
	public function getContext()
	{
		return $this->context;
	}

	/**
	 * @param string $context
	 *
	 * @return $this
	 */
	public function setContext( $context )
	{
		$this->context = $context;

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