<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\SysConf_URI;
use Jet\Tr;

class JetStudio_Module_Manifest extends BaseObject {
	protected string $name = '';
	protected string $namespace = '';
	protected string $base_dir = '';
	protected string $vendor = '';
	protected string $version = '';
	protected string $label = '';
	protected string $url_path_part = '';
	protected string $icon = '';
	protected int $sort_order = 0;
	
	public function __construct( string $name, string $namespace, string $base_dir, array $manifest_data )
	{
		$this->name = $name;
		$this->namespace = $namespace;
		$this->base_dir = $base_dir;
		$this->vendor = $manifest_data['vendor']??'';
		$this->version = $manifest_data['version']??'';
		$this->label = $manifest_data['label']??'';
		$this->url_path_part = $manifest_data['url_path_part']??'';
		$this->icon = $manifest_data['icon']??'';
		$this->sort_order = $manifest_data['sort_order']??0;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function getNamespace(): string
	{
		return $this->namespace;
	}
	
	
	
	public function getBaseDir(): string
	{
		return $this->base_dir;
	}
	
	public function getVendor(): string
	{
		return $this->vendor;
	}
	
	public function getVersion(): string
	{
		return $this->version;
	}
	
	public function getLabel(): string
	{
		return $this->label;
	}
	
	public function getLabelTranslated() : string
	{
		return Tr::_( $this->getLabel(), dictionary: $this->getDictionaryName() );
	}
	
	public function getUrlPathPart(): string
	{
		return $this->url_path_part;
	}
	
	public function getIcon(): string
	{
		return $this->icon;
	}
	
	public function getDictionaryName() : string
	{
		return 'Module.'.$this->name;
	}
	
	public function getURL() : string
	{
		if(!$this->url_path_part) {
			return '';
		}
		
		return SysConf_URI::getBase().$this->url_path_part.'/';
	}
	
	public function getSortOrder(): int
	{
		return $this->sort_order;
	}
	
	
}