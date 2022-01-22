<?php
/**
 *
 * @copyright 
 * @license  
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\Data_Listing;
use Jet\DataModel_Fetch_Instances;

use JetApplication\Logger_Web_Event as Event;


/**
 *
 */
class Listing extends Data_Listing {

	/**
	 * @var array
	 */
	protected array $grid_columns = [
		'id'                  => ['title' => 'ID'],
		'date_time'           => ['title' => 'Date time'],
		'event_class'         => ['title' => 'Event class'],
		'event'               => ['title' => 'Event'],
		'event_message'       => ['title' => 'Event message'],
		'context_object_id'   => ['title' => 'Context object ID'],
		'context_object_name' => ['title' => 'Context object name'],
		'user_id'             => ['title' => 'User ID'],
		'user_username'       => ['title' => 'User name'],
	];

	protected string $default_sort = '-id';

	/**
	 *
	 */
	protected function initFilters(): void
	{
		$this->filters['search']         = new Listing_Filter_Search( $this );
		$this->filters['event_class']    = new Listing_Filter_EventClass( $this );
		$this->filters['event']          = new Listing_Filter_Event( $this );
		$this->filters['date_time']      = new Listing_Filter_DateTime( $this );
		$this->filters['user']           = new Listing_Filter_User( $this );
		$this->filters['context_object'] = new Listing_Filter_ContextObject( $this );
	}


	/**
	 * @return Event[]|DataModel_Fetch_Instances
	 * @noinspection PhpDocSignatureInspection
	 */
	protected function getList() : DataModel_Fetch_Instances
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return Event::getList();
	}


}