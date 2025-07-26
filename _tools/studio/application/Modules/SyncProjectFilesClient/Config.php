<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\SyncProjectFilesClient;

use Jet\Config_Definition;
use Jet\Form_Definition;
use Jet\Form_Field;
use JetStudio\JetStudio_Module_Config;
use Jet\Config as JetConfig;

#[Config_Definition(
	name: 'SyncProjectFilesClient'
)]
class Config extends JetStudio_Module_Config {
	
	#[Form_Definition(
		type: Form_Field::TYPE_TEXTAREA,
		label: 'Allowed file extensions:',
		is_required: false,
		error_messages: [
		]
	)]
	#[Config_Definition(
		type: JetConfig::TYPE_STRING
	)]
	protected string $allowed_extensions = 'php
phtml
js
css';
	
	#[Form_Definition(
		type: Form_Field::TYPE_TEXTAREA,
		label: 'Blacklist:',
		is_required: false,
		error_messages: [
		]
	)]
	#[Config_Definition(
		type: JetConfig::TYPE_STRING
	)]
	protected string $blacklist = '_backup
_installer
_profiler
_tools
_var_dump
application/config
application/data
js/packages
css/packages
images
cache
logs
tmp';
	
	public function getAllowedExtensions(): string
	{
		return $this->allowed_extensions;
	}
	
	public function setAllowedExtensions( string $allowed_extensions ): void
	{
		$this->allowed_extensions = $allowed_extensions;
	}
	
	public function getBlacklist(): string
	{
		return $this->blacklist;
	}
	
	public function setBlacklist( string $blacklist ): void
	{
		$this->blacklist = $blacklist;
	}
	
	
	
}