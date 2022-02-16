<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Data_Paginator extends BaseObject implements BaseObject_Interface_Serializable_JSON
{
	/**
	 * @var int
	 */
	protected int $items_per_page = 20;

	/**
	 * @var array|Data_Paginator_DataSource|null
	 */
	protected array|Data_Paginator_DataSource|null $data = null;

	/**
	 * @var int
	 */
	protected int $data_items_count = 0;

	/**
	 * @var int
	 */
	protected int $pages_count = 0;

	/**
	 * @var int
	 */
	protected int $current_page_no = 0;

	/**
	 *
	 * @var int|null
	 */
	protected int|null $prev_page_no = 0;

	/**
	 *
	 * @var int|null
	 */
	protected int|null $next_page_no = 0;

	/**
	 *
	 * @var int
	 */
	protected int $data_index_start = 0;

	/**
	 *
	 * @var int
	 */
	protected int $data_index_end = 0;

	/**
	 * @var bool
	 */
	protected bool $current_page_no_is_in_range = false;

	/**
	 * @var callable
	 */
	protected $URL_creator;

	/**
	 *
	 * @var string|null
	 */
	protected string|null $prev_page_URL = null;

	/**
	 *
	 * @var string|null
	 */
	protected string|null $next_page_URL = null;

	/**
	 * @var string|null
	 */
	protected string|null $first_page_URL = null;

	/**
	 * @var string|null
	 */
	protected string|null $last_page_URL = null;

	/**
	 * @var array
	 */
	protected array $pages_URL = [];

	/**
	 *
	 * @param int $current_page_no
	 * @param int $items_per_page
	 * @param callable $URL_creator
	 *
	 */
	public function __construct( int $current_page_no, int $items_per_page, callable $URL_creator )
	{

		$this->current_page_no_is_in_range = true;

		$this->current_page_no = $current_page_no;
		$this->items_per_page = $items_per_page;

		if( $this->current_page_no < 1 ) {
			$this->current_page_no_is_in_range = false;
			$this->current_page_no = 1;
		}

		if( $this->items_per_page < 1 ) {
			$this->items_per_page = 1;
		}

		if( $this->items_per_page > SysConf_Jet_Data_Paginator::getMaxItemsPerPage() ) {
			$this->items_per_page = SysConf_Jet_Data_Paginator::getMaxItemsPerPage();
		}


		$this->URL_creator = $URL_creator;
	}

	/**
	 * @param callable $URL_creator
	 */
	public function setURLCreator( callable $URL_creator ): void
	{
		$this->URL_creator = $URL_creator;
	}

	/**
	 *
	 */
	protected function _calculate(): void
	{

		$this->pages_count = (int)ceil( $this->data_items_count / $this->items_per_page );

		if( !$this->pages_count ) {
			$this->pages_count = 1;
		}


		if( $this->current_page_no > $this->pages_count ) {
			$this->current_page_no_is_in_range = false;
			$this->current_page_no = $this->pages_count;
		}

		$this->next_page_no = $this->current_page_no + 1;
		if( $this->next_page_no > $this->pages_count ) {
			$this->next_page_no = null;
		}
		$this->prev_page_no = $this->current_page_no - 1;
		if( $this->prev_page_no < 1 ) {
			$this->prev_page_no = null;
		}
		$this->data_index_start = ($this->current_page_no - 1) * $this->items_per_page;
		$this->data_index_end = ($this->data_index_start + $this->items_per_page) - 1;

		if( $this->data_index_end >= $this->data_items_count ) {
			$this->data_index_end = $this->data_items_count - 1;
		}

		$this->prev_page_URL = null;
		$this->next_page_URL = null;
		$this->pages_URL = [];


		$creator = $this->URL_creator;


		$this->first_page_URL = $creator( 1 );
		$this->last_page_URL = $creator( $this->pages_count );

		if( $this->prev_page_no ) {
			$this->prev_page_URL = $creator( $this->prev_page_no );
		}

		if( $this->next_page_no ) {
			$this->next_page_URL = $creator( $this->next_page_no );
		}

		if( $this->pages_count ) {
			for( $i = 1; $i <= $this->pages_count; $i++ ) {
				$this->pages_URL[$i] = $creator( $i );
			}
		}

	}


	/**
	 *
	 * @param Data_Paginator_DataSource $data
	 */
	public function setDataSource( Data_Paginator_DataSource $data ): void
	{
		$this->data = $data;

		$this->data_items_count = $data->getCount();
		$this->_calculate();

		$this->data->setPagination( $this->items_per_page, $this->data_index_start );
	}

	/**
	 *
	 * @param array|Data_Paginator_DataSource $data
	 */
	public function setData( array|Data_Paginator_DataSource $data ): void
	{
		if( $data instanceof Data_Paginator_DataSource ) {
			$this->setDataSource( $data );
			return;
		}

		$this->data = $data;
		$this->data_items_count = count( $data );
		$this->_calculate();
	}


	/**
	 *
	 * @return iterable
	 * @throws Data_Paginator_Exception
	 *
	 */
	public function getData(): iterable
	{
		if( $this->data === null ) {
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

		for( $i = $this->data_index_start; $i <= $this->data_index_end; $i++ ) {
			if( !isset( $data_map[$i] ) ) {
				break;
			}

			$result[$data_map[$i]] = $this->data[$data_map[$i]];
		}

		return $result;
	}

	/**
	 *
	 * @return int
	 */
	public function getDataItemsCount(): int
	{
		return $this->data_items_count;
	}

	/**
	 *
	 * @return int
	 */
	public function getPagesCount(): int
	{
		return $this->pages_count;
	}

	/**
	 * @return int
	 */
	public function getCurrentPageNo(): int
	{
		return $this->current_page_no;
	}

	/**
	 *
	 * @return int|null
	 */
	public function getPrevPageNo(): int|null
	{
		return $this->prev_page_no;
	}

	/**
	 *
	 * @return int|null
	 */
	public function getNextPageNo(): int|null
	{
		return $this->next_page_no;
	}

	/**
	 *
	 * @return int
	 */
	public function getDataIndexStart(): int
	{
		return $this->data_index_start;
	}

	/**
	 *
	 * @return int
	 */
	public function getDataIndexEnd(): int
	{
		return $this->data_index_end;
	}

	/**
	 * @return int
	 */
	public function getShowFrom(): int
	{
		if( $this->data_items_count ) {
			return $this->data_index_start + 1;
		} else {
			return 0;
		}
	}

	/**
	 * @return int
	 */
	public function getShowTo(): int
	{
		if( $this->data_items_count ) {
			$show = $this->data_index_end + 1;
			if( $show > $this->data_items_count ) {
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
	public function getCurrentPageNoIsInRange(): bool
	{
		return $this->current_page_no_is_in_range;
	}

	/**
	 *
	 * @return null|string
	 */
	public function getPrevPageURL(): null|string
	{
		return $this->prev_page_URL;
	}

	/**
	 *
	 * @return null|string
	 */
	public function getNextPageURL(): null|string
	{
		return $this->next_page_URL;
	}

	/**
	 * @return null|string
	 */
	public function getLastPageURL(): null|string
	{
		return $this->last_page_URL;
	}

	/**
	 * @return null|string
	 */
	public function getFirstPageURL(): null|string
	{
		return $this->first_page_URL;
	}


	/**
	 * @return array
	 */
	public function getPagesURL(): array
	{
		return $this->pages_URL;
	}

	/**
	 * @return string
	 */
	public function toJSON(): string
	{
		return json_encode( $this->jsonSerialize() );
	}

	/**
	 *
	 * @return array
	 */
	public function jsonSerialize(): array
	{

		$items = $this->getData();

		if( $items instanceof Data_Paginator_DataSource ) {
			$items = $items->jsonSerialize();
		}

		return [
			'pagination' => [
				'items_per_page' => $this->items_per_page,

				'data_items_count' => $this->data_items_count,

				'pages_count' => $this->pages_count,

				'current_page_no' => $this->current_page_no,

				'prev_page_no' => $this->prev_page_no,
				'next_page_no' => $this->next_page_no,

				'prev_page_URL'  => $this->prev_page_URL,
				'next_page_URL'  => $this->next_page_URL,
				'first_page_URL' => $this->first_page_URL,
				'last_page_URL'  => $this->last_page_URL,
			],
			'items'      => $items
		];
	}
}