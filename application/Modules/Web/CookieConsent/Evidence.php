<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Web\CookieConsent;

use Jet\Data_DateTime;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_AutoIncrement;
use Jet\Http_Request;
use Jet\Locale;
use Jet\MVC;


#[DataModel_Definition(
	id_controller_class: DataModel_IDController_AutoIncrement::class,
	id_controller_options: ['id_property_name'=>'id']
)]
abstract class Evidence extends DataModel
{
	#[DataModel_Definition(
		type: DataModel::TYPE_ID_AUTOINCREMENT,
		is_id: true,
	)]
	protected int $id = 0;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100
	)]
	protected string $base_id = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_LOCALE
	)]
	protected ?Locale $locale = null;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100
	)]
	protected string $IP = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME
	)]
	protected ?Data_DateTime $date_time = null;
	
	public function getBaseId(): string
	{
		return $this->base_id;
	}
	
	public function setBaseId( string $base_id ): void
	{
		$this->base_id = $base_id;
	}
	
	public function getLocale(): ?Locale
	{
		return $this->locale;
	}
	
	public function setLocale( ?Locale $locale ): void
	{
		$this->locale = $locale;
	}
	
	
	public function getIP(): string
	{
		return $this->IP;
	}
	
	public function setIP( string $IP ): void
	{
		$this->IP = $IP;
	}
	
	public function getDateTime(): Data_DateTime
	{
		return $this->date_time;
	}
	
	public function setDateTime( Data_DateTime $date_time ): void
	{
		$this->date_time = $date_time;
	}
	
	public function afterUpdate(): void
	{
	}
	
	public function afterDelete(): void
	{
	}
	
	public function afterAdd(): void
	{
	}
	
	protected function performBasicEvidence() : void
	{
		$this->setBaseId( MVC::getBase()->getId() );
		$this->setLocale( MVC::getLocale() );
		$this->setIP( Http_Request::clientIP() );
		$this->setDateTime( Data_DateTime::now() );
		
	}
}
