<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_TestModule
 */
namespace JetApplicationModule\Vendor\Package\TestModule;
use Jet;
use Jet\Mvc_Dispatcher_Queue_Item;
use Jet\Mvc_Router_Abstract;

class Main extends Jet\Application_Modules_Module_Abstract {
	public function getMyValue(){
		return 'My value';
	}

	public function testAck(Jet\Application_Signals_Signal $signal){
		echo 'TestModule: ACK signal received.\n';
		var_dump($signal->getName(), $signal->getData(), $signal->getSender());
	}

	public function sendReceived(){
		echo 'TestModule: sending test/received\n';
		return $this->sendSignal('/test/received', 'HELLO!');
	}

	public function sendMultiple(){
		echo 'TestModule: sending test/multiple\n';
		return $this->sendSignal('/test/multiple', 'HELLO MULTIPLE!');
	}

	public function testInstall() {
		//echo 'Hello! This is TestModule install script!\n';
		file_put_contents(
			JET_TESTS_TMP.'module-install-test',
			'Hello! This is TestModule install script!\n'
		);
	}

	public function testUninstall() {
		//echo 'Hello! This is TestModule uninstall script!\n';
		unlink(JET_TESTS_TMP.'module-install-test');
	}

	/**
	 * @param Mvc_Router_Abstract $router
	 * @param Mvc_Dispatcher_Queue_Item $dispatch_queue_item
	 */
	public function resolveRequest(Mvc_Router_Abstract $router, Mvc_Dispatcher_Queue_Item $dispatch_queue_item) {
	}
}