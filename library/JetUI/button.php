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

class button extends BaseObject
{

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $type = 'button';

    /**
     * @var string
     */
    protected $class = 'default';

	/**
	 * @var string
	 */
	protected $size = 'normal';

    /**
     * @var string
     */
    protected $icon = '';

    /**
     * @var string
     */
    protected $icon_class = UI::DEFAULT_ICON_CLASS;

    /**
     * @var string
     */
    protected $onclick = '';

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @param string $label
     */
    public function __construct( $label ) {
        $this->label = $label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        /**
         * @var button $this
         */
        $this->label = $label;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        /**
         * @var button $this
         */
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        /**
         * @var button $this
         */
        $this->class = $class;

        return $this;
    }

	/**
	 * @param string $size
	 *
	 * @return button
	 */
	public function setSize($size)
	{
		/**
		 * @var button $this
		 */
		$this->size = $size;

		return $this;
	}

    /**
     * @param string $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        /**
         * @var button $this
         */
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param string $icon_class
     *
     * @return $this
     */
    public function setIconClass($icon_class)
    {
        /**
         * @var button $this
         */
        $this->icon_class = $icon_class;
        return $this;
    }

    /**
     * @param string $onclick
     *
     * @return $this
     */
    public function setOnclick($onclick)
    {
        /**
         * @var button $this
         */
        $this->onclick = $onclick;
        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }



    /**
     * @return string
     */
    public function toString()
    {

        if($this->url) {
            $res = '';
            $res .= '<a href="'.$this->url.'" class="btn btn-'.$this->class.' btn-'.$this->size.'"';
            if($this->onclick) {
                $res .= ' onclick="'.$this->onclick.'"';
            }
            $res .= '>';
            if($this->icon_class && $this->icon) {
                $res .= '<span class="'.$this->icon_class.$this->icon.'"></span> ';
            }
            $res .= $this->label;
            $res .= '</a>';

            return $res;
        }

        $res = '';
        $res .= '<button type="'.$this->type.'" class="btn btn-'.$this->class.' btn-'.$this->size.'"';
        if($this->onclick) {
            $res .= ' onclick="'.$this->onclick.'"';
        }
        $res .= '>';
	    if($this->icon_class && $this->icon) {
		    $res .= '<span class="'.$this->icon_class.$this->icon.'"></span> ';
	    }
	    $res .= $this->label;
	    $res .= '</button>';

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