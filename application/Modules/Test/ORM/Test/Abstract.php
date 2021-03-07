<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\Tr;

/**
 *
 */
abstract class Test_Abstract
{
	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 *
	 * @param string $id
	 */
	public function __construct( string $id )
	{
		$this->id = $id;

	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getTitle() : string
	{
		return Tr::_( $this->_getTitle() );
	}

	/**
	 * @return string
	 */
	abstract protected function _getTitle() : string;

	/**
	 *
	 */
	abstract public function test() : void;

}