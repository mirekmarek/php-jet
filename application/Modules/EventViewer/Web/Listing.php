<?php
/**
 *
 * @copyright 
 * @license  
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\Form;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\Logger;
use Jet\Tr;
use Jet\Data_Listing;
use Jet\Data_Listing_Filter_search;
use Jet\DataModel_Fetch_Instances;

use JetApplication\Logger_Web_Event as Event;


/**
 *
 */
class Listing extends Data_Listing {

	use Data_Listing_Filter_search;

	use Listing_filter_eventClass;
	use Listing_filter_event;
	use Listing_filter_dateTime;
	use Listing_filter_user;
	use Listing_filter_contextObject;

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
	 * @var string[]
	 */
	protected array $filters = [
		'search',
		'eventClass',
		'event',
		'dateTime',
		'user',
		'contextObject',
	];

	/**
	 * @return Event[]|DataModel_Fetch_Instances
	 * @noinspection PhpDocSignatureInspection
	 */
	protected function getList() : DataModel_Fetch_Instances
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return Event::getList();
	}

	/**
	 *
	 */
	protected function filter_search_getWhere() : void
	{
		if(!$this->search) {
			return;
		}

		$search = '%'.$this->search.'%';
		$this->filter_addWhere([
			'event *'        => $search,
			'OR',
			'event_class *' => $search,
			'OR',
			'event_message *' => $search,
		]);
	}






	/**
	 * @var string
	 */
	protected string $event_class = '';


	/**
	 *
	 */
	protected function filter_eventClass_catchGetParams(): void
	{
		$this->event_class = Http_Request::GET()->getString( 'event_class' );
		$this->setGetParam( 'event_class', $this->event_class );
	}

	/**
	 * @param Form $form
	 */
	public function filter_eventClass_catchForm( Form $form ): void
	{
		$value = $form->field( 'event_class' )->getValue();

		$this->event_class = $value;
		$this->setGetParam( 'event_class', $value );
	}

	/**
	 * @param Form $form
	 */
	protected function filter_eventClass_getForm( Form $form ): void
	{
		$field = new Form_Field_Select( 'event_class', 'Event class:', $this->event_class );
		$field->setErrorMessages( [
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => ' '
		] );
		$options = [
			'' => Tr::_( '- all -' ),
			Logger::EVENT_CLASS_SUCCESS => Tr::_('success'),
			Logger::EVENT_CLASS_INFO => Tr::_('info'),
			Logger::EVENT_CLASS_WARNING => Tr::_('warning'),
			Logger::EVENT_CLASS_DANGER => Tr::_('danger'),
			Logger::EVENT_CLASS_FAULT => Tr::_('fault'),
		];

		$field->setSelectOptions( $options );


		$form->addField( $field );
	}

	/**
	 *
	 */
	protected function filter_eventClass_getWhere(): void
	{
		if( $this->event_class ) {
			$this->filter_addWhere( [
				'event_class' => $this->event_class,
			] );
		}
	}

}