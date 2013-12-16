<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @param \Jet\Object $sender
	 * @param string $name
	 * @param array $data (optional)
	 */
	public function __construct( Object $sender, $name, array $data=array() ) {
	}

}
