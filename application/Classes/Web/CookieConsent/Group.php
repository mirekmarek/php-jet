<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

class Web_CookieConsent_Group
{
	public const STATS = 'stats';
	public const MARKETING = 'marketing';
	public const MESUREMENT = 'mesurement';
	
	protected string $code = '';
	
	protected string $title = '';
	
	protected string $description = '';
	
	protected bool $checked = false;
	
	protected ?bool $enabled = null;
	
	
	public function getCode() : string
	{
		return $this->code;
	}
	
	public function setCode( string $code ) : void
	{
		$this->code = $code;
	}
	
	public function getTitle() : string
	{
		return $this->title;
	}
	
	public function setTitle( string $title ) : void
	{
		$this->title = $title;
	}
	
	public function getDescription() : string
	{
		return $this->description;
	}
	
	public function setDescription( string $description ) : void
	{
		$this->description = $description;
	}
	
	public function getChecked() : bool
	{
		return $this->checked;
	}
	
	public function setChecked( bool $checked ) : void
	{
		$this->checked = $checked;
	}
	
	
	public function getEnabled() : bool
	{
		return Application_Service_Web::CookieConsent()->groupEnabled($this->code);
	}
	
	public function enable() : void
	{
		Application_Service_Web::CookieConsent()->enableGroup( $this->code );
	}
	
	public function disable() : void
	{
		Application_Service_Web::CookieConsent()->disableGroup($this->code);
	}
	
}