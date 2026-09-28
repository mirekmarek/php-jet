<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Web\CookieConsent;


use Jet\Data_DateTime;
use JetApplication\Application_Service_Web;

class Consent {
	protected ?Data_DateTime $date_set = null;
	protected ?Data_DateTime $date_last_update = null;
	
	/**
	 * @var array<string,bool|null>
	 */
	protected array $groups = [];
	
	public function __construct( string $data='' )
	{
		foreach( Application_Service_Web::CookieConsent()->getGroups() as $group) {
			$this->groups[ $group->getCode() ] = null;
		}
		
		if($data) {
			$data = json_decode(base64_decode($data), true);
			if(isset($data['date_set'])) {
				$this->date_set = Data_DateTime::catchDateTime( $data['date_set'] );
			}
			
			if(isset($data['date_last_update'])) {
				$this->date_last_update = Data_DateTime::catchDateTime( $data['date_last_update'] );
			}
			
			if(!empty($data['groups'])) {
				foreach( explode('|', $data['groups']) as $d ) {
					$d=explode('=', $d);
					
					if(
						count($d)==2 &&
						array_key_exists($d[0], $this->groups)
					) {
						$this->groups[$d[0]] = (bool)$d[1];
					}
				}
			}
		}
	}
	
	public function initNew() : void
	{
		foreach($this->groups as $group=>$agree) {
			$this->groups[$group] = false;
		}
		$this->setDateSet( Data_DateTime::now() );
		$this->setDateLastUpdate( Data_DateTime::now() );
	}
	
	public function isValid() : bool
	{
		foreach($this->groups as $group=>$agree) {
			if($agree===null) {
				return false;
			}
		}
		
		return true;
	}
	
	public function getDateSet(): ?Data_DateTime
	{
		return $this->date_set;
	}

	public function setDateSet( ?Data_DateTime $date_set ): void
	{
		$this->date_set = $date_set;
	}
	
	public function getDateLastUpdate(): ?Data_DateTime
	{
		return $this->date_last_update;
	}
	
	public function setDateLastUpdate( ?Data_DateTime $date_last_update ): void
	{
		$this->date_last_update = $date_last_update;
	}
	
	/**
	 * @return array<string>
	 */
	public function getEnabledGroups(): array
	{
		$groups = [];
		foreach($this->groups as $group=>$agree) {
			if($agree) {
				$groups[] = $group;
			}
		}
		
		return $groups;
	}
	
	/**
	 * @param array<string> $groups
	 * @return void
	 */
	public function enableGroups( array $groups ): void
	{
		foreach($this->groups as $gr=>$state) {
			$this->groups[$gr] = in_array($gr, $groups);
		}
	}
	
	public function isAgree() : bool
	{
		foreach($this->groups as $group=>$agree) {
			if($agree) {
				return true;
			}
		}
		
		return false;
	}
	
	public function isCompleteAgree() : bool
	{
		foreach($this->groups as $group=>$agree) {
			if(!$agree) {
				return false;
			}
		}
		
		return true;
	}
	
	public function __toString() : string
	{
		$this->setDateLastUpdate( Data_DateTime::now() );
		
		$groups = [];
		foreach($this->groups as $group=>$agree) {
			if($agree!==null) {
				$groups[] = $group.'='.($agree ? 1 : 0);
			}
		}
		
		$groups = implode('|', $groups);
		
		$data = [
			'date_set' => $this->date_set ? $this->date_set->toString():'',
			'date_last_update' => $this->date_last_update ? $this->date_last_update->toString() : '',
			'groups' => $groups
		];
		
		$data = json_encode($data, flags: JSON_THROW_ON_ERROR);
		
		return base64_encode($data);
	}
	
	public function toString() : string
	{
		return $this->__toString();
	}
	
}