<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


/**
 *
 */
class UI_messages_message extends UI_Renderer_Single
{

	/**
	 * @var string
	 */
	protected string $class = '';

	/**
	 * @var string
	 */
	protected string $message = '';

	/**
	 * @var string
	 */
	protected string $context = '';

	/**
	 * @var string
	 */
	protected string $icon = '';

	/**
	 * @var bool
	 */
	protected bool $closeable = true;


	/**
	 *
	 * @param string $class
	 * @param string $message
	 * @param string $context
	 */
	public function __construct( string $class, string $message, string $context = '' )
	{
		$this->class = $class;
		$this->message = $message;
		$this->context = $context;
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('messages/message' );
	}
	
	/**
	 * @return string
	 */
	public function getIcon(): string
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function setIcon( string $icon ): static
	{
		$this->icon = $icon;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getClass(): string
	{
		return $this->class;
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function setClass( string $class ): static
	{
		$this->class = $class;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 *
	 * @return $this
	 */
	public function setMessage( string $message ): static
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getContext(): string
	{
		return $this->context;
	}

	/**
	 * @param string $context
	 *
	 * @return $this
	 */
	public function setContext( string $context ): static
	{
		$this->context = $context;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getIsCloseable(): bool
	{
		return $this->closeable;
	}

	/**
	 * @param bool $closeable
	 *
	 * @return $this
	 */
	public function setCloseable( bool $closeable ): static
	{
		$this->closeable = $closeable;

		return $this;
	}


}