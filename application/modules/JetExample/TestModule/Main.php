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
 */
namespace JetApplicationModule\JetExample\TestModule;

use Jet\Application_Signals_Signal;
use Jet\Application_Modules_Module_Abstract;

class Main extends Application_Modules_Module_Abstract {
    /**
     * @return string
     */
    public function getMyValue(){
		return 'My value';
	}

    /**
     * @param Application_Signals_Signal $signal
     */
    public function testAck(Application_Signals_Signal $signal){
		echo 'TestModule: ACK signal received.\n';
		var_dump($signal->getName(), $signal->getData(), $signal->getSender());
	}

    /**
     * @return Application_Signals_Signal
     */
    public function sendReceived(){
		echo 'TestModule: sending test/received\n';
		return $this->sendSignal('test/received', 'HELLO!');
	}

    /**
     * @return Application_Signals_Signal
     */
    public function sendMultiple(){
		echo 'TestModule: sending test/multiple\n';
		return $this->sendSignal('test/multiple', 'HELLO MULTIPLE!');
	}

    public function testInstall() {

    }

}