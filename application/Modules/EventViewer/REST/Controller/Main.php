<?php
/**
 *
 * @copyright 
 * @license  
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\REST;

use Jet\Http_Request;
use JetApplication\Logger_REST_Event as Event;

use Jet\MVC;
use Jet\MVC_Page;
use Jet\MVC_Controller_Default;
use Jet\UI;
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
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation( string $current_label = '' ) : void
	{
		/**
		 * @var MVC_Page $page
		 */
		$page = MVC::getPage();

		Navigation_Breadcrumb::reset();

		Navigation_Breadcrumb::addURL(
			UI::icon( $page->getIcon() ) . '&nbsp;&nbsp;' . $page->getBreadcrumbTitle(),
			$page->getURL()
		);

		if( $current_label ) {
			Navigation_Breadcrumb::addURL( $current_label );
		}
	}

	/**
	 *
	 */
	public function listing_Action() : void
	{
		$this->_setBreadcrumbNavigation();

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

		$this->_setBreadcrumbNavigation( Tr::_( 'View event <b>%ITEM_NAME%</b>', [ 'ITEM_NAME' => '['.$event->getId().'] '.$event->getEventMessage() ] ) );

		$this->view->setVar( 'event', $event );

		$this->output( 'view' );

	}

}