<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 * @JetConfig:name = 'mailing'
 */
class Mailing_Config extends Config
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
	 * @param string|Locale $locale
	 * @param string $site_id
	 * @param string $specification
	 *
	 * @return Mailing_Config_Sender
	 *
	 */
	public function getSender( $locale, $site_id, $specification )
	{

		/**
		 * @var Mailing_Config_Sender $sender
		 */
		$sender = $this->senders->getConfigurationListItem( $this->getSenderKey( $locale, $site_id, $specification ) );

		return $sender;
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
	 * @param Mailing_Config_Sender $sender_configuration
	 * @param string|Locale $locale
	 * @param string $site_id
	 * @param string $specification
	 *
	 */
	public function addSender( Mailing_Config_Sender $sender_configuration, $locale, $site_id, $specification )
	{
		$this->senders->addConfigurationItem(
			$this->getSenderKey( $locale, $site_id, $specification ),
			$sender_configuration
		);
	}

	/**
	 * @param string $key
	 */
	public function deleteSender( $key )
	{
		$this->senders->deleteConfigurationItem( $key );
	}

	/**
	 * @param string|Locale $locale
	 * @param string $site_id
	 * @param string $specification
	 *
	 * @return string
	 */
	public function getSenderKey( $locale, $site_id, $specification )
	{
		$key = (string)$locale;

		if(!$site_id) {
			$site_id = 'ALL';
		}

		$key .= ':'.$site_id;


		if($specification) {
			$key .= ':'.$specification;
		}

		return $key;
	}
}
