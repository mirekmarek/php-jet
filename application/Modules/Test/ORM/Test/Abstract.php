<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\Tr;

/**
 *
 */
abstract class Test_Abstract
{
	/**
	 * @var
	 */
	protected $id = '';

	/**
	 *
	 * @param string $id
	 */
	public function __construct( $id )
	{
		$this->id = $id;

	}

	/**
	 * @return mixed
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
		return Tr::_( $this->_getTitle() );
	}

	/**
	 * @return string
	 */
	abstract protected function _getTitle();

	/**
	 *
	 */
	abstract public function test();

}