<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\OAuth\Backend\Google;

use Jet\Config_Definition;
use Jet\Form_Definition;
use JetApplication\OAuth_BackendModule_Config;

#[Config_Definition(
	name: 'OAuth-Google'
)]
class Config_Specific extends OAuth_BackendModule_Config
{
	#[Config_Definition]
	#[Form_Definition]
	protected string $oauth_endpoint_URL = 'https://accounts.google.com/o/oauth2/auth';
	
	#[Config_Definition]
	#[Form_Definition]
	protected string $token_endpoint_URL = 'https://accounts.google.com/o/oauth2/token';
	
	#[Config_Definition]
	#[Form_Definition]
	protected string $user_detail_endpoint_URL = 'none';
	
}