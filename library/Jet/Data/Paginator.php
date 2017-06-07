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
class Data_Paginator extends BaseObject implements BaseObject_Serializable_JSON
{

	/**
	 * @var int
	 */
	protected static $max_items_per_page = 500;


	/**
	 * @var int
	 */
	protected $items_per_page;

	/**
	 * @var array|DataModel_Fetch_Data
	 */
	protected $data;

	/**
	 * @var int
	 */
	protected $data_items_count = 0;

	/**
	 * @var int
	 */
	protected $pages_count = 0;

	/**
	 * @var int
	 */
	protected $current_page_no = 0;

	/**
	 * Can be null if current page is the first page
	 *
	 * @var int|null
	 */
	protected $prev_page_no = 0;

	/**
	 * Can be null if current page is the last page
	 *
	 * @var int|null
	 */
	protected $next_page_no = 0;

	/**
	 *
	 * @var int
	 */
	protected $data_index_start = 0;

	/**
	 *
	 * @var int
	 */
	protected $data_index_end = 0;

	/**
	 * @var bool
	 */
	protected $current_page_no_is_in_range = false;

	/**
	 * @var callable
	 */
	protected $URL_creator;

	/**
	 * Can be null if current page is the first page
	 *
	 * @var string|null
	 */
	protected $prev_page_URL = null;

	/**
	 * Can be null if current page is the last page
	 *
	 * @var string|null
	 */
	protected $next_page_URL = null;

	/**
	 * @var string|null
	 */
	protected $first_page_URL = null;

	/**
	 * @var string|null
	 */
	protected $last_page_URL = null;

	/**
	 * @var array
	 */
	protected $pages_URL = [];

	/**
	 *
	 * @param int    $current_page_no (no 1 is first)
	 * @param int    $items_per_page
	 * @param callable $URL_creator
	 *
	 * @throws Data_Paginator_Exception
	 */
	public function __construct( $current_page_no, $items_per_page, callable $URL_creator )
	{

		$this->current_page_no_is_in_range = true;

		$this->current_page_no = (int)$current_page_no;
		$this->items_per_page = (int)$items_per_page;

		if( $this->current_page_no<1 ) {
			$this->current_page_no_is_in_range = false;
			$this->current_page_no = 1;
		}

		if( $this->items_per_page<1 ) {
			$this->items_per_page = 1;
		}

		if( $this->items_per_page>static::$max_items_per_page ) {
			$this->items_per_page = static::$max_items_per_page;
		}


		$this->URL_creator = $URL_creator;
	}

	/**
	 * @param callable $URL_creator
	 */
	public function setURLCreator( callable $URL_creator )
	{
		$this->URL_creator = $URL_creator;
	}

	/**
	 * Calculates pagination properties
	 */
	protected function _calculate()
	{

		$this->pages_count = (int)ceil( $this->data_items_count/$this->items_per_page );

		if(!$this->pages_count) {
			$this->pages_count = 1;
		}


		if( $this->current_page_no>$this->pages_count ) {
			$this->current_page_no_is_in_range = false;
			$this->current_page_no = $this->pages_count;
		}

		$this->next_page_no = $this->current_page_no+1;
		if( $this->next_page_no>$this->pages_count ) {
			$this->next_page_no = null;
		}
		$this->prev_page_no = $this->current_page_no-1;
		if( $this->prev_page_no<1 ) {
			$this->prev_page_no = null;
		}
		$this->data_index_start = ( $this->current_page_no-1 )*$this->items_per_page;
		$this->data_index_end = ( $this->data_index_start+$this->items_per_page )-1;

		if( $this->data_index_end>=$this->data_items_count ) {
			$this->data_index_end = $this->data_items_count-1;
		}

		$this->prev_page_URL = null;
		$this->next_page_URL = null;
		$this->pages_URL = [];


		$creator = $this->URL_creator;


		$this->first_page_URL = $creator( 1 );
		$this->last_page_URL = $creator($this->pages_count);

		if( $this->prev_page_no ) {
			$this->prev_page_URL = $creator( $this->prev_page_no );
		}

		if( $this->next_page_no ) {
			$this->next_page_URL = $creator($this->next_page_no);
		}

		if( $this->pages_count ) {
			for( $i = 1; $i<=$this->pages_count; $i++ ) {
				$this->pages_URL[$i] = $creator($i);
			}
		}

	}


	/**
	 * Sets paginated data source
	 *
	 * @param Data_Paginator_DataSource $data
	 */
	public function setDataSource( Data_Paginator_DataSource $data )
	{
		$this->data = $data;

		$this->data_items_count = $data->getCount();
		$this->_calculate();

		$this->data->setPagination( $this->items_per_page, $this->data_index_start );
	}

	/**
	 * Sets paginated data
	 *
	 * @param array $data
	 */
	public function setData( array $data )
	{
		$this->data = $data;
		$this->data_items_count = count( $data );
		$this->_calculate();
	}


	/**
	 * Returns current page data
	 *
	 * @throws Data_Paginator_Exception
	 * @return array|DataModel_Fetch_Data
	 */
	public function getData()
	{
		if( $this->data===null ) {
			throw new Data_Paginator_Exception(
				'Data source is not set! Please call ->setData() or ->setDataSource() first ',
				Data_Paginator_Exception::CODE_DATA_SOURCE_IS_NOT_SET
			);
		}

		if( $this->data instanceof Data_Paginator_DataSource ) {
			return $this->data;
		}

		$data_map = array_keys( $this->data );

		$result = [];

		for( $i = $this->data_index_start; $i<=$this->data_index_end; $i++ ) {
			$result[$data_map[$i]] = $this->data[$data_map[$i]];
		}

		return $result;
	}

	/**
	 *
	 * @return int
	 */
	public function getDataItemsCount()
	{
		return $this->data_items_count;
	}

	/**
	 *
	 * @return int
	 */
	public function getPagesCount()
	{
		return $this->pages_count;
	}

	/**
	 * @return int
	 */
	public function getCurrentPageNo()
	{
		return $this->current_page_no;
	}

	/**
	 * Can be null if current page is the first page
	 *
	 * @return int|null
	 */
	public function getPrevPageNo()
	{
		return $this->prev_page_no;
	}

	/**
	 * Can be null if current page is the last page
	 *
	 * @return int|null
	 */
	public function getNextPageNo()
	{
		return $this->next_page_no;
	}

	/**
	 *
	 * @return int
	 */
	public function getDataIndexStart()
	{
		return $this->data_index_start;
	}

	/**
	 *
	 * @return int
	 */
	public function getDataIndexEnd()
	{
		return $this->data_index_end;
	}

	/**
	 * @return int
	 */
	public function getShowFrom()
	{
		if( $this->data_items_count ) {
			return $this->data_index_start+1;
		} else {
			return 0;
		}
	}

	/**
	 * @return int
	 */
	public function getShowTo()
	{
		if( $this->data_items_count ) {
			$show = $this->data_index_end+1;
			if( $show>$this->data_items_count ) {
				$show = $this->data_items_count;
			}

			return $show;
		} else {
			return 0;
		}
	}

	/**
	 *
	 * @return bool
	 */
	public function getCurrentPageNoIsInRange()
	{
		return $this->current_page_no_is_in_range;
	}

	/**
	 * Can be null if current page is the first page
	 *
	 * @return null|string
	 */
	public function getPrevPageURL()
	{
		return $this->prev_page_URL;
	}

	/**
	 * Can be null if current page is the last page
	 *
	 * @return null|string
	 */
	public function getNextPageURL()
	{
		return $this->next_page_URL;
	}

	/**
	 * @return null|string
	 */
	public function getLastPageURL()
	{
		return $this->last_page_URL;
	}

	/**
	 * @return null|string
	 */
	public function getFirstPageURL()
	{
		return $this->first_page_URL;
	}


	/**
	 * @return array
	 */
	public function getPagesURL()
	{
		return $this->pages_URL;
	}

	/**
	 * @return string
	 */
	public function toJSON()
	{
		return json_encode($this->jsonSerialize());
	}

	/**
	 * 
	 * @return array
	 */
	public function jsonSerialize()
	{

		$items = $this->getData();
		
		if( $items instanceof Data_Paginator_DataSource ) {
			$items = $items->jsonSerialize();
		}
		
		return [
			'pagination' => [
				'items_per_page' => $this->items_per_page,

				'data_items_count' => $this->data_items_count,

				'pages_count'      => $this->pages_count,

				'current_page_no'  => $this->current_page_no,

				'prev_page_no'     => $this->prev_page_no,
				'next_page_no'     => $this->next_page_no,

				'prev_page_URL'    => $this->prev_page_URL,
				'next_page_URL'    => $this->next_page_URL,
				'first_page_URL'   => $this->first_page_URL,
				'last_page_URL'    => $this->last_page_URL,
			],
	    	'items' => $items
		];
	}
}