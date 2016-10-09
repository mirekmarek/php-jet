<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Application
 * @subpackage Application_Signals
 */
namespace Jet;

class FakeSignalClass {
	/**
	 *
	 * @param Object_Interface $sender
	 * @param string $name
	 * @param array $data (optional)
	 */
	public function __construct( Object_Interface $sender, $name, array $data= []) {
	}

}
