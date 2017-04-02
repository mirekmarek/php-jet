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
use Jet\Http_Request;

class tabs_tab extends BaseObject{


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
	 *
	 * @param string $id
	 * @param string $title
	 */
	public function __construct($id, $title)
	{
		$this->id = $id;
		$this->title = $title;
	}

	/**
	 * @return boolean
	 */
	public function getIsSelected()
	{
		return $this->is_selected;
	}

	/**
	 * @param boolean $is_selected
	 */
	public function setIsSelected($is_selected)
	{
		$this->is_selected = $is_selected;
	}

	/**
	 * @return string
	 */
	public function toString() {
		$url = Http_Request::getCurrentURI(['p'=>$this->id]);

		return '<li'.($this->is_selected?' class="active"':'').'><a href="'.$url.'">'.$this->title.'</a></li>';
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

}