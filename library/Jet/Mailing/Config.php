<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @JetConfig:type = Config::TYPE_SECTIONS
	 * @JetConfig:section_creator_method_name = 'createSenderConfigInstance'
	 *
	 * @var Mailing_Config_Sender[]
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

		$key = $this->getSenderKey( $locale, $site_id, $specification );

		if(!isset($this->senders[$key])) {
			return null;
		}

		return $this->senders[$key];
	}

	/**
	 * @return Mailing_Config_Sender[]
	 */
	public function getSenders()
	{
		return $this->senders;
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
		$this->senders[ $this->getSenderKey( $locale, $site_id, $specification ) ] = $sender_configuration;
	}

	/**
	 * @param string $key
	 */
	public function deleteSender( $key )
	{
		if(isset($this->senders[$key])) {
			unset( $this->senders[$key] );
		}
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

		$key .= '/'.$site_id;


		if($specification) {
			$key .= '/'.$specification;
		}

		return $key;
	}

	/**
	 * @param array $data
	 *
	 * @return Mailing_Config_Sender
	 */
	public function createSenderConfigInstance( array $data )
	{
		return new Mailing_Config_Sender($data);
	}
}
