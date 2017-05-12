<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

use Jet\BaseObject;
use Jet\Mvc_View;


/**
 *
 */
class dialog extends BaseObject
{

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var int
	 */
	protected $width = 0;

	/**
	 *
	 * @param string $id
	 * @param string $title
	 * @param int    $width
	 */
	public function __construct( $id, $title, $width )
	{
		$this->id = $id;
		$this->title = $title;
		$this->width = $width;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}


	/**
	 * @return Mvc_View
	 */
	public function getView() {

		$view = UI::getView();
		$view->setVar( 'element', $this );

		return $view;

	}


	/**
	 * @return string
	 */
	public function start()
	{
		return $this->getView()->render('dialog/start');
	}

	/**
	 * @return string
	 */
	public function footer()
	{
		return $this->getView()->render('dialog/footer');
	}

	/**
	 * @return string
	 */
	public function end()
	{
		return $this->getView()->render('dialog/end');
	}

}