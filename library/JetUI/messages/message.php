<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetUI;
use Jet\BaseObject;

class messages_message extends BaseObject{


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
		messages::C_INFO => 'info-circle',
		messages::C_SUCCESS => 'thumbs-up',
		messages::C_WARNING => 'exclamation-circle',
		messages::C_DANGER => 'thumbs-down',
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
			<button type="button" class="close" data-dismiss="alert" aria-label="" onclick="setTimeout( function() {jetshop_admin_main.adjustManRowHeight()}, 10 );"><span aria-hidden="true">&times;</span></button>
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