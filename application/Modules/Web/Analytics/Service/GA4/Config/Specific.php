<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Web\Analytics\Service\GA4;



use Jet\Config_Definition;
use Jet\Form_Definition;
use Jet\Form_Definition_Interface;
use Jet\Form_Definition_Trait;
use Jet\Form_Field;
use JetApplication\AppConfig_ModuleConfig_Specific;
use Jet\Config;

#[Config_Definition(
	name: 'GA4'
)]
class Config_Specific extends AppConfig_ModuleConfig_Specific implements Form_Definition_Interface {
	use Form_Definition_Trait;
	
	#[Config_Definition(
		type: Config::TYPE_STRING,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Google ID: ',
	)]
	protected string $google_id = '';


	#[Config_Definition(
		type: Config::TYPE_BOOL
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_CHECKBOX,
		label: 'Native mode'
	)]
	protected bool $native_mode = true;

	public function getGoogleId(): string
	{
		return $this->google_id;
	}
	
	public function setGoogleId( string $google_id ): void
	{
		$this->google_id = $google_id;
	}
	
	public function getNativeMode(): bool
	{
		return $this->native_mode;
	}
	
	public function setNativeMode( bool $native_mode ): void
	{
		$this->native_mode = $native_mode;
	}
	
	
}