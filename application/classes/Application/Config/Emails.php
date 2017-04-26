<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;

use Jet\Application_Config;
use Jet\Config_Definition_Property_ConfigList;

/**
 *
 * @JetConfig:data_path = 'emails'
 */
class Application_Config_Emails extends Application_Config {

	/**
	 * @JetConfig:type = Config::TYPE_CONFIG_LIST
	 * @JetConfig:data_path = 'senders'
	 * @JetConfig:item_class_name = 'JetExampleApp\Application_Config_Emails_Sender'
	 *
	 * @var Config_Definition_Property_ConfigList
	 */
	protected $senders;


	/**
	 *
	 * @param $locale
	 *
	 * @return Application_Config_Emails_Sender
	 */
	public function getSender($locale){
		$locale = (string)$locale;
		return $this->senders->getConfigurationListItem( $locale );
	}

	/**
	 * @return Application_Config_Emails_Sender[]
	 */
	public function getSenders() {
		/**
		 * @var Application_Config_Emails_Sender[] $c_cfg
		 */
		$c_cfg = $this->senders->getAllConfigurationItems();
		return $c_cfg;
	}

	/**
	 * @param string $locale
	 * @param Application_Config_Emails_Sender $sender_configuration
	 *
	 */
	public function addSender( $locale, Application_Config_Emails_Sender $sender_configuration ) {
		$locale = (string)$locale;
		$this->senders->addConfigurationItem( $locale, $sender_configuration );
	}

	/**
	 * @param string $locale
	 *
	 */
	public function deleteSender( $locale ) {
		$locale = (string)$locale;
		$this->senders->deleteConfigurationItem( $locale );
	}

}
