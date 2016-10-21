<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;
use Jet\BaseObject;

class UI_messages_message extends BaseObject{


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
	protected $icon_class = UI::DEFAULT_ICON_CLASS;

	/**
	 * @var string
	 */
	protected $icon = 'exclamation-sign';

	/**
	 * @var array
	 */
	protected static $icons_map = [
		UI_messages::C_INFO => 'exclamation-sign',
		UI_messages::C_SUCCESS => 'thumbs-up',
		UI_messages::C_WARNING => 'exclamation-sign',
		UI_messages::C_DANGER => 'thumbs-down',
	];

	/**
	 *
	 * @param string $class
	 * @param string $message
	 */
	public function __construct($class, $message)
	{
		$this->class = $class;
		$this->message = $message;

		if(isset(static::$icons_map[$class])) {
			$this->icon = static::$icons_map[$class];
		}

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
	 */
	public function setClass($class)
	{
		$this->class = $class;
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
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function toString() {
		return '<div class="alert alert-'.$this->getClass().'" role="alert">
			<span class="'.$this->icon_class.$this->icon.'"></span>
			<button type="button" class="close" data-dismiss="alert" aria-label=""><span aria-hidden="true">&times;</span></button>
			'.$this->getMessage().'
		</div>';

	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

}