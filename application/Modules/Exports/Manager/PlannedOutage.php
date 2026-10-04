<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Exports\Manager;


use Jet\Data_DateTime;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_AutoIncrement;
use JetApplication\Exports_Definition;

#[DataModel_Definition(
	name: 'export_planned_outage',
	database_table_name: 'exports_planned_outages',
	id_controller_class: DataModel_IDController_AutoIncrement::class,
)]
class PlannedOutage extends DataModel {
	
	#[DataModel_Definition(
		type: DataModel::TYPE_ID_AUTOINCREMENT,
		is_id: true
	)]
	protected int $id = 0;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $export_code = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME,
		is_key: true
	)]
	protected ?Data_DateTime $from_date_time = null;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME,
		is_key: true
	)]
	protected ?Data_DateTime $till_date_time = null;
	
	public static function new( Exports_Definition $export, ?Data_DateTime $from_date_time, ?Data_DateTime $till_date_time ) : static
	{
		$outage = new static();
		$outage->export_code = $export->getCode();
		$outage->from_date_time = $from_date_time;
		$outage->till_date_time = $till_date_time;
		$outage->save();
		
		return $outage;
	}
	
	/**
	 * @return static[]
	 */
	public static function getList( string $export_code ) : array
	{
		$all = static::fetch([''=>[
			'export_code' => $export_code
		]]);
		
		$list = [];
		foreach($all as $outage) {
			if(
				$outage->till_date_time &&
				$outage->till_date_time<Data_DateTime::now()
			) {
				$outage->delete();
				continue;
			}
			
			$list[] = $outage;
		}
		
		return $list;
	}
	
	
	public function getId(): int
	{
		return $this->id;
	}
	
	
	public function getExportCode(): string
	{
		return $this->export_code;
	}
	
	public function setExportCode( string $export_code ): void
	{
		$this->export_code = $export_code;
	}
	
	public function getFromDateTime(): ?Data_DateTime
	{
		return $this->from_date_time;
	}
	
	public function setFromDateTime( ?Data_DateTime $from_date_time ): void
	{
		$this->from_date_time = $from_date_time;
	}
	
	public function getTillDateTime(): ?Data_DateTime
	{
		return $this->till_date_time;
	}
	
	public function setTillDateTime( ?Data_DateTime $till_date_time ): void
	{
		$this->till_date_time = $till_date_time;
	}
	
	public function isValid() : bool
	{
		if( $this->from_date_time && $this->from_date_time>Data_DateTime::now() ) {
			return false;
		}
		
		if( $this->till_date_time && $this->till_date_time<Data_DateTime::now() ) {
			return false;
		}
		
		return true;
	}
	
	
}