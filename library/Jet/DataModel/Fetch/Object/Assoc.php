<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Fetch_Object_Assoc
 * @package Jet
 */
class DataModel_Fetch_Object_Assoc extends DataModel_Fetch_Object implements Data_Paginator_DataSource, \ArrayAccess, \Iterator, \Countable
{

	/**
	 * @var array|DataModel_PropertyFilter
	 */
	protected $load_filter;

	/**
	 * @return array|DataModel_PropertyFilter
	 */
	public function getLoadFilter()
	{
		return $this->load_filter;
	}

	/**
	 * @param array|DataModel_PropertyFilter $load_filter
	 */
	public function setLoadFilter( $load_filter )
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
	public function _fetch()
	{
		if( $this->data!==null ) {
			return;
		}

		$this->data = [];

		$backend = $this->data_model_definition->getBackendInstance();

		$l = $backend->fetchAll( $this->query );


		if( $this->load_filter ) {
			foreach( $l as $item ) {
				$l_id = clone $this->empty_id_instance;

				foreach( $l_id as $k => $v ) {
					$l_id[$k] = $item[$k];
				}


				$this->data[(string)$l_id] = [
					'__id' => $l_id, '__data' => $item,
				];
			}
		} else {
			foreach( $l as $item ) {
				$l_id = clone $this->empty_id_instance;

				foreach( $l_id as $k => $v ) {
					$l_id[$k] = $item[$k];
				}

				$this->data[(string)$l_id] = [
					'__id' => $l_id,
				];
			}
		}
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$result = [];

		foreach( $this as $key => $val ) {
			/**
			 * @var DataModel $val
			 */
			$result[$key] = $val->jsonSerialize();
		}

		return $result;
	}

	/**
	 * @param array $item
	 *
	 * @return DataModel
	 */
	protected function _get( $item )
	{

		if( isset( $item['__instance'] ) ) {
			return $item['__instance'];
		}
		$class_name = $this->data_model_definition->getClassName();

		if( isset( $item['__data'] ) ) {
			/**
			 * @var DataModel $_i
			 */
			$_i = new $class_name();
			$_i->setLoadFilter( $this->load_filter );
			$_i->setState( $item['__data'], $_i->loadMainRelatedData() );

			$_i->afterLoad();
		} else {
			/**
			 * @var DataModel $class_name
			 */
			$_i = $class_name::load( $item['__id'], $this->load_filter );
		}

		$this->data[$_i->getIdObject()->toString()]['__instance'] = $_i;


		return $_i;
	}


}