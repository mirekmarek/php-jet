<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	#[Config_Definition(
		type: Config::TYPE_SECTIONS,
		section_creator_method_name: 'createSenderConfigInstance'
	)]
	#[Form_Definition(is_sub_forms: true)]
	protected array|null $senders = null;


	/**
	 *
	 * @param string $id
	 *
	 * @return Mailing_Config_Sender|null
	 *
	 */
	public function getSender( string $id ): Mailing_Config_Sender|null
	{

		if( !isset( $this->senders[$id] ) ) {
			return null;
		}

		return $this->senders[$id];
	}

	/**
	 * @return Mailing_Config_Sender[]|null
	 */
	public function getSenders(): array|null
	{
		return $this->senders;
	}

	/**
	 * @param string $id
	 * @param Mailing_Config_Sender $sender_configuration
	 *
	 */
	public function addSender( string $id, Mailing_Config_Sender $sender_configuration )
	{
		$this->senders[$id] = $sender_configuration;
	}

	/**
	 * @param string $id
	 */
	public function deleteSender( string $id )
	{
		if( isset( $this->senders[$id] ) ) {
			unset( $this->senders[$id] );
		}
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
