<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
	 *
	 */
	public function __construct()
	{
	}

	/**
	 * @return string
	 */
	abstract public function getId(): string;
	
	/**
	 * @return string
	 */
	abstract protected function _getTitle() : string;
	
	
	/**
	 * @return string
	 */
	public function getTitle() : string
	{
		return Tr::_( $this->_getTitle() );
	}

	
	/**
	 *
	 */
	abstract public function test() : void;

}