<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Config;
use Jet\Config;
use Jet\Config_Definition_Property_ConfigList;

/**
 *
 * @JetConfig:data_path = 'emails'
 */
class Mailing_Config extends Application_Config
{

	/**
	 * @JetConfig:type = Config::TYPE_CONFIG_LIST
	 * @JetConfig:data_path = 'senders'
	 * @JetConfig:item_class_name = 'Mailing_Config_Sender'
	 *
	 * @var Config_Definition_Property_ConfigList
	 */
	protected $senders;


	/**
	 *
	 * @param $locale
	 *
	 * @return Mailing_Config_Sender
	 */
	public function getSender( $locale )
	{
		$locale = (string)$locale;

		return $this->senders->getConfigurationListItem( $locale );
	}

	/**
	 * @return Mailing_Config_Sender[]
	 */
	public function getSenders()
	{
		/**
		 * @var Mailing_Config_Sender[] $c_cfg
		 */
		$c_cfg = $this->senders->getAllConfigurationItems();

		return $c_cfg;
	}

	/**
	 * @param string                $locale
	 * @param Mailing_Config_Sender $sender_configuration
	 *
	 */
	public function addSender( $locale, Mailing_Config_Sender $sender_configuration )
	{
		$locale = (string)$locale;
		$this->senders->addConfigurationItem( $locale, $sender_configuration );
	}

	/**
	 * @param string $locale
	 *
	 */
	public function deleteSender( $locale )
	{
		$locale = (string)$locale;
		$this->senders->deleteConfigurationItem( $locale );
	}

}
