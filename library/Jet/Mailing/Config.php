<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;


/**
 *
 */
#[Config_Definition(name: 'mailing')]
class Mailing_Config extends Config
{

	/**
	 *
	 * @var Mailing_Config_Sender[]
	 */
	#[Config_Definition(type: Config::TYPE_SECTIONS)]
	#[Config_Definition(section_creator_method_name: 'createSenderConfigInstance')]
	protected array|null $senders = null;


	/**
	 *
	 * @param string|Locale $locale
	 * @param string $site_id
	 * @param string $specification
	 *
	 * @return Mailing_Config_Sender|null
	 *
	 */
	public function getSender( string|Locale $locale, string $site_id, string $specification ): Mailing_Config_Sender|null
	{

		$key = $this->getSenderKey( $locale, $site_id, $specification );

		if( !isset( $this->senders[$key] ) ) {
			return null;
		}

		return $this->senders[$key];
	}

	/**
	 * @return Mailing_Config_Sender[]|null
	 */
	public function getSenders(): array|null
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
	public function addSender( Mailing_Config_Sender $sender_configuration, string|Locale $locale, string $site_id, string $specification )
	{
		$this->senders[$this->getSenderKey( $locale, $site_id, $specification )] = $sender_configuration;
	}

	/**
	 * @param string $key
	 */
	public function deleteSender( string $key )
	{
		if( isset( $this->senders[$key] ) ) {
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
	public function getSenderKey( string|Locale $locale, string $site_id, string $specification ): string
	{
		$key = (string)$locale;

		if( !$site_id ) {
			$site_id = 'ALL';
		}

		$key .= '/' . $site_id;


		if( $specification ) {
			$key .= '/' . $specification;
		}

		return $key;
	}

	/**
	 * @param array $data
	 *
	 * @return Mailing_Config_Sender
	 */
	public function createSenderConfigInstance( array $data ): Mailing_Config_Sender
	{
		return new Mailing_Config_Sender( $data );
	}
}
