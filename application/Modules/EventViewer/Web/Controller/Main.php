<?php
/**
 *
 * @copyright 
 * @license  
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\Http_Request;
use Jet\MVC_View;
use JetApplication\Logger_Web_Event as Event;

use Jet\MVC_Controller_Default;
use Jet\Tr;
use Jet\Navigation_Breadcrumb;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	protected ?Event $event = null;
	
	protected ?Listing $listing = null;


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
	
	protected function getListing() : Listing
	{
		if(!$this->listing) {
			$this->listing = new Listing(
				controller:  $this,
				column_view: new MVC_View( $this->view->getScriptsDir().'list/column/' ),
				filter_view: new MVC_View( $this->view->getScriptsDir().'list/filter/' )
			);
			
			$this->listing->handle();
		}
		
		return $this->listing;
	}

	/**
	 *
	 */
	public function listing_Action() : void
	{
		$listing = $this->getListing();
		
		$this->view->setVar( 'listing', $listing );

		$this->output( 'list' );
	}


	/**
	 *
	 */
	public function view_Action() : void
	{
		$event = $this->event;
		$listing = $this->getListing();
		
		$list_uri = $listing->getURI();
		
		if(($prev_item_id = $listing->getPrevItemId( $event->getId() ))) {
			$this->view->setVar( 'prev_item_url', $listing->getItemURI( $prev_item_id ) );
		}
		if(($next_item_id = $listing->getNextItemId( $event->getId() ))) {
			$this->view->setVar( 'next_item_url', $listing->getItemURI( $next_item_id ) );
		}
		
		Navigation_Breadcrumb::getItems()[1]->setURL( $list_uri );
		Navigation_Breadcrumb::addURL( Tr::_( 'View event <b>%ITEM_NAME%</b>', [ 'ITEM_NAME' => '['.$event->getId().'] '.$event->getEventMessage() ] ) );

		$this->view->setVar( 'list_url', $list_uri );
		$this->view->setVar( 'event', $event );

		$this->output( 'view' );
	}

}