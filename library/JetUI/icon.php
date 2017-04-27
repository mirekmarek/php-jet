<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;
use Jet\BaseObject;

/**
 * Class icon
 * @package JetUI
 */
class icon extends BaseObject
{

	/**
	 * @var string
	 */
	protected $tag = 'span';

    /**
     * @var string
     */
    protected $icon;

	/**
	 * @var int
	 */
	protected $size;

	/**
	 * @var int
	 */
	protected $width;

	/**
	 * @var string
	 */
	protected $color;

	/**
	 * @var array
	 */
	protected $js_actions = [];


    /**
     * @param string $icon
     */
    public function __construct( $icon ) {
        $this->icon = $icon;
    }

	/**
	 * @param string $event
	 * @param string $handler_code
	 *
	 * @return icon
	 */
	public function setJsAction( $event, $handler_code )
	{
		/**
		 * @var icon $this
		 */

		$event = strtolower($event);

		if(!isset($this->js_actions[$event])) {
			$this->js_actions[$event] = $handler_code;
		} else {
			$this->js_actions[$event] .= ';'.$handler_code;

		}

		return $this;
	}


	/**
	 * @param $size
	 *
	 * @return icon
	 */
	public function setSize($size)
	{
		$this->size = (int)$size;

		return $this;
	}

	/**
	 * @param $width
	 *
	 * @return icon
	 */
	public function setWidth($width)
	{
		$this->width = (int)$width;

		return $this;
	}

	/**
	 * @param string $color
	 *
	 * @return icon
	 */
	public function setColor($color)
	{
		$this->color = $color;

		return $this;
	}

	/**
	 * @param string $tag
	 *
	 * @return icon
	 */
	public function setTag($tag)
	{
		$this->tag = $tag;

		return $this;
	}

    /**
     * @return string
     */
    public function toString()
    {
    	$icon = $this->icon;

        $res = '';

	    $style = '';

	    if($this->size) {
	    	$style .= 'font-size:'.$this->size.'px;';
	    }
	    if($this->width) {
		    $style .= 'width:'.$this->width.'px;';
	    }
	    if($this->color) {
		    $style .= 'color:'.$this->color.';';
	    }

	    $res .= '<'.$this->tag.' class="'.UI::DEFAULT_ICON_CLASS.$icon.'"';
	    if($style) {
		    $res .= ' style="'.$style.'"';
	    }

	    $js_actions = [];

	    foreach( $this->js_actions as $vent=>$handler ) {
		    $js_actions[] = ' '.$vent.'="'.$handler.'"';
	    }
	    $res .= implode('', $js_actions);

	    $res .= '></'.$this->tag.'>';


        return $res;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

}