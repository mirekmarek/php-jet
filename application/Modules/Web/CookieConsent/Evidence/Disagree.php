<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Web\CookieConsent;

use Jet\DataModel_Definition;


#[DataModel_Definition(
	name: 'cookie_consent_evidence_disagree',
	database_table_name: 'cookie_consent_evidence_disagree',
)]
class Evidence_Disagree extends Evidence
{
	public static function performEvidence() : void
	{
		$i = new static();
		$i->performBasicEvidence();
		$i->save();
		
	}
}
