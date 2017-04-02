<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetUI;

use Jet\BaseObject;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Session;
use Jet\Tr;

class searchForm extends BaseObject
{

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var Session
	 */
	protected $session;

	/**
	 * @var string
	 */
	protected static $search_key = 'search';


	/**
	 * @param string $name
	 */
	public function __construct( $name ) {
		$this->name = $name;
		$this->session = new Session('search_form_'.$name);

		$POST = Http_Request::POST();
		if($POST->exists(static::$search_key)) {
			$this->session->setValue('search', $POST->getString(static::$search_key));
			Http_Headers::reload();
		}
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->session->getValue('search', '');
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		$value = $this->getValue();

		$url = Http_Request::getCurrentURI(['p'=>0]);

		$res = '<form method="post" name="search_form_'.$this->name.'" id="search_form_'.$this->name.'" action="'.$url.'">
			<div class="input-group">
				<span class="input-group-btn"><button class="btn btn-default" type="button" onclick="this.form.'.static::$search_key.'.value=\'\';this.form.submit();">'.UI::icon('times').'</button></span>
				<input type="text" class="form-control" placeholder="'.Tr::_('Search for...', [], Tr::COMMON_NAMESPACE).'" name="'.static::$search_key.'" value="'.$value.'">
				<span class="input-group-btn"><button class="btn btn-default" type="submit">'.UI::icon('search').'</button></span>
			</div>
			</form>';

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