<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Web\CookieConsent;

use Jet\DataModel;
use Jet\DataModel_Definition;

#[DataModel_Definition(
	name: 'cookie_consent_evidence_agree',
	database_table_name: 'cookie_consent_evidence_agree',
)]
class Evidence_Agree extends Evidence
{
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $groups = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_BOOL
	)]
	protected bool $complete_consent = false;
	
	public function getGroups(): array
	{
		if(!$this->groups) {
			return [];
		}
		return explode('|', $this->groups);
	}
	
	public function setGroups( array $groups ): void
	{
		$this->groups = implode('|', $groups);
	}
	
	public function getCompleteConsent(): bool
	{
		return $this->complete_consent;
	}
	
	public function setCompleteConsent( bool $complete_consent ): void
	{
		$this->complete_consent = $complete_consent;
	}
	
	public static function performEvidence( array $enabled_groups, bool $complete_consent ) : void
	{
		$i = new static();
		$i->performBasicEvidence();
		
		$i->setGroups( $enabled_groups );
		$i->setCompleteConsent( $complete_consent );
		$i->save();
		
	}
	
}
