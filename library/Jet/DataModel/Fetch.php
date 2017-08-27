<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
abstract class DataModel_Fetch extends BaseObject implements BaseObject_Interface_Serializable_JSON, Data_Paginator_DataSource
{

	/**
	 * DataModel instance
	 *
	 * @var DataModel_Definition_Model
	 */
	protected $data_model_definition;

	/**
	 * Query
	 *
	 * @var DataModel_Query
	 */
	protected $query;

	/**
	 * @var int
	 */
	protected $count = null;

	/**
	 * @var bool
	 */
	protected $pagination_enabled = false;

	/**
	 *
	 * @param DataModel_Query $query
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query $query )
	{
		$this->data_model_definition = $query->getMainDataModelDefinition();

		$this->query = $query;
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 */
	public function setPagination( $limit, $offset )
	{
		$this->pagination_enabled = true;

		$this->query->setLimit( $limit, $offset );
	}

	/**
	 * @return DataModel_Query
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * Returns data count
	 *
	 * @return int
	 */
	public function getCount()
	{
		if( $this->count===null ) {
			$this->count = DataModel_Backend::get($this->data_model_definition)->getCount( $this->query );
		}

		return $this->count;
	}

	/**
	 *
	 */
	abstract protected function _fetch();

}