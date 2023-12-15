<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\EventViewer\Admin;

use Jet\Factory_MVC;
use Jet\Http_Request;

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
	
	protected string $export_key = '';
	
	
	public function resolve(): bool|string
	{
		$GET = Http_Request::GET();
		if(
			($event_id=$GET->getInt('id')) &&
			($this->event=Event::get($event_id))
		) {
			return 'view';
		}
		
		if(
			($export_key=$GET->getString('export')) &&
			$this->getListing()->exportExists($export_key)
		)  {
			$this->export_key = $export_key;
			return 'export';
		}
		
		return 'listing';
	}
	
	protected function getListing() : Listing
	{
		if(!$this->listing) {
			$column_view = Factory_MVC::getViewInstance( $this->view->getScriptsDir().'list/column/' );
			$column_view->setController( $this );
			$filter_view = Factory_MVC::getViewInstance( $this->view->getScriptsDir().'list/filter/' );
			$filter_view->setController( $this );
			
			$this->listing = new Listing(
				column_view: $column_view,
				filter_view: $filter_view
			);
		}
		
		return $this->listing;
	}
	
	public function listing_Action() : void
	{
		$listing = $this->getListing();
		$listing->handle();
		
		$this->view->setVar( 'listing', $listing );
		
		$this->output( 'list' );
	}
	
	public function export_Action() : void
	{
		$listing = $this->getListing();
		$listing->handle();
		$listing->export( $this->export_key )->export();
	}
	
	
	public function view_Action() : void
	{
		$event = $this->event;
		$listing = $this->getListing();
		$listing->handle();
		
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