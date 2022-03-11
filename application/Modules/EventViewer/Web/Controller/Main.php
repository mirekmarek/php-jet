<?php
/**
 *
 * @copyright 
 * @license  
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\Http_Request;
use JetApplication\Logger_Web_Event as Event;

use Jet\MVC_Controller_Default;
use Jet\Tr;
use Jet\Navigation_Breadcrumb;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 * @var ?Event
	 */
	protected ?Event $event = null;


	public function resolve(): bool|string
	{
		$GET = Http_Request::GET();
		if(
			($event_id=$GET->getInt('id')) &&
			($this->event=Event::get($event_id))
		) {
			return 'view';
		}

		return 'listing';
	}
	
	/**
	 *
	 */
	public function listing_Action() : void
	{
		$listing = new Listing();
		$listing->handle();

		$this->view->setVar( 'filter_form', $listing->getFilterForm());
		$this->view->setVar( 'grid', $listing->getGrid() );

		$this->output( 'list' );
	}


	/**
	 *
	 */
	public function view_Action() : void
	{
		$event = $this->event;
		
		Navigation_Breadcrumb::addURL( Tr::_( 'View event <b>%ITEM_NAME%</b>', [ 'ITEM_NAME' => '['.$event->getId().'] '.$event->getEventMessage() ] ) );

		$this->view->setVar( 'event', $event );

		$this->output( 'view' );

	}

}