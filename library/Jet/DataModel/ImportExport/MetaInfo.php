<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class DataModel_ImportExport_MetaInfo extends BaseObject implements BaseObject_Interface_Serializable_JSON
{
	public const META_INFO_FILE_NAME = 'jet_datamodel_data_export_meta_info.json';
	

	protected string $data_model_class_name;
	protected string $source_backend_type;
	protected ?array $source_where;
	protected int $record_count;
	protected Data_DateTime $export_date_time;
	
	public static function fromJSON( string $json ): static
	{
		$data = json_decode( $json, true );
		$meta_info = new static();
		
		$meta_info->setDataModelClassName( $data['data_model_class_name'] );
		$meta_info->setSourceBackendType( $data['source_backend_type'] );
		$meta_info->setSourceWhere( $data['source_where'] );
		$meta_info->setRecordCount( $data['record_count'] );
		$meta_info->setExportDateTime( new Data_DateTime($data['export_date_time']) );
		
		return $meta_info;
	}
	
	public function toJSON(): string
	{
		return json_encode( $this );
	}
	
	public function jsonSerialize(): mixed
	{
		$data = get_object_vars( $this );
		$data['export_date_time'] = $this->export_date_time->toString();
		return $data;
	}

	public static function read( string $meta_info_file_path ) : static
	{
		$json = IO_File::read( $meta_info_file_path );
		return static::fromJSON( $json );
	}
	
	public function save( string $target_dir_path ) : void
	{
		$path = $target_dir_path. '/' . self::META_INFO_FILE_NAME;
		IO_File::write( $path, $this->toJSON() );
	}
	
	
	public function getDataModelClassName(): string
	{
		return $this->data_model_class_name;
	}
	
	public function setDataModelClassName( string $data_model_class_name ): void
	{
		$this->data_model_class_name = $data_model_class_name;
	}
	
	public function getSourceBackendType(): string
	{
		return $this->source_backend_type;
	}
	
	public function setSourceBackendType( string $source_backend_type ): void
	{
		$this->source_backend_type = $source_backend_type;
	}
	
	public function getSourceWhere(): array
	{
		return $this->source_where;
	}
	
	public function setSourceWhere( ?array $source_where ): void
	{
		$this->source_where = $source_where;
	}
	
	public function getRecordCount(): int
	{
		return $this->record_count;
	}
	
	public function setRecordCount( int $record_count ): void
	{
		$this->record_count = $record_count;
	}
	
	public function getExportDateTime(): Data_DateTime
	{
		return $this->export_date_time;
	}
	
	public function setExportDateTime( Data_DateTime $export_date_time ): void
	{
		$this->export_date_time = $export_date_time;
	}
}