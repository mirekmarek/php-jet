<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_TestModule
 */
namespace JetApplicationModule\Jet\TestModule;
use Jet;

class Main extends Jet\Application_Modules_Module_Abstract {
	function getMyValue(){
		return "My value";
	}
	
	function testAck(Jet\Application_Signals_Signal $signal){
		echo "TestModule: ACK signal received.\n";
		var_dump($signal->getName(), $signal->getData(), $signal->getSender());
	}
	
	function sendReceived(){
		echo "TestModule: sending test/received\n";
		return $this->sendSignal("test/received", "HELLO!");
	}
	
	function sendMultiple(){
		echo "TestModule: sending test/multiple\n";
		return $this->sendSignal("test/multiple", "HELLO MULTIPLE!");
	}
}