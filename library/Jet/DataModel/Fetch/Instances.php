<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 *
 */
class DataModel_Fetch_Instances extends DataModel_Fetch implements Data_Paginator_DataSource, BaseObject_Interface_ArrayEmulator
{

	/**
	 * @var array|DataModel_PropertyFilter|null
	 */
	protected array|DataModel_PropertyFilter|null $load_filter = null;

	/**
	 * @var array|null|DataModel[]
	 */
	protected ?array $_instances = null;

	/**
	 * @var array
	 */
	protected array $_where = [];

	/**
	 * @return array|DataModel_PropertyFilter
	 */
	public function getLoadFilter() : array|DataModel_PropertyFilter
	{
		return $this->load_filter;
	}

	/**
	 * @param array|DataModel_PropertyFilter $load_filter
	 */
	public function setLoadFilter( array|DataModel_PropertyFilter $load_filter ) : void
	{
		if( $load_filter ) {

			if( !( $load_filter instanceof DataModel_PropertyFilter ) ) {
				$load_filter = new DataModel_PropertyFilter(
					$this->data_model_definition, $load_filter
				);
			}

			$this->query->setSelect(
				DataModel_PropertyFilter::getQuerySelect( $this->data_model_definition, $load_filter )
			);

		}

		$this->load_filter = $load_filter;
	}

	/**
	 *
	 */
	protected function _fetch() : void
	{
		if( $this->data!==null ) {
			return;
		}

		$this->data = [];

		$l = DataModel_Backend::get($this->data_model_definition)->fetchAll( $this->query );

		foreach( $l as $item ) {
			$l_id = clone $this->empty_id_instance;

			foreach( $l_id->getPropertyNames() as $k ) {
				$l_id->setValue( $k, $item[$k]);

				if(!isset($this->_where[$k])) {
					$this->_where[$k] = [];
				}

				$this->_where[$k][] =  $item[$k];
			}

			$i_id_str = (string)$l_id;

			$this->data[$i_id_str] = $i_id_str;
		}
	}

	/**
	 * @return array
	 */
	public function toArray() : array
	{
		$result = [];

		foreach( $this as $key => $val ) {
			$result[$key] = $val->jsonSerialize();
		}

		return $result;
	}

	/**
	 * @param mixed $item
	 *
	 * @return DataModel
	 */
	protected function _get( mixed $item ) : DataModel
	{

		if( $this->_instances===null ) {

			/**
			 * @var DataModel $class_name
			 */
			$class_name = $this->data_model_definition->getClassName();
			$model_name = $this->data_model_definition->getModelName();

			$this->_instances = $class_name::fetch(
				[
					$model_name=>$this->_where
				],
				null,
				function( DataModel $item ) {
					return $item->getIDController()->toString();
				},
				$this->load_filter
			);
		}

		return $this->_instances[$item];
	}


}