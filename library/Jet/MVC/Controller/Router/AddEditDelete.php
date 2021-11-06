<?php

namespace Jet;

/**
 *
 */
class MVC_Controller_Router_AddEditDelete extends MVC_Controller_Router
{

	/**
	 *
	 * @param MVC_Controller $controller
	 * @param callable $item_catcher
	 * @param array $actions_map
	 */
	public function __construct( MVC_Controller $controller, callable $item_catcher, array $actions_map = [] )
	{
		parent::__construct( $controller );

		$GET = Http_Request::GET();
		$action = $GET->getString( 'action' );
		$id = $GET->getString( 'id' );

		$this->setDefaultAction( 'listing', $actions_map['listing'] ?? '' )
			->setURICreator( function() {
				return Http_Request::currentURI( [], [
					'id',
					'action'
				] );
			} );

		$this->addAction( 'add', $actions_map['add'] ?? '' )
			->setResolver( function() use ( $action ) {
				return ($action == 'add');
			} )
			->setURICreator( function() {
				return Http_Request::currentURI( ['action' => 'add'], ['id'] );
			} );

		$this->addAction( 'edit', $actions_map['edit'] ?? '' )
			->setResolver( function() use ( $item_catcher, $action, $id ) {
				if( $action != '' ) {
					return false;
				}
				return $item_catcher( $id );
			} )
			->setURICreator( function( $id ) {
				return Http_Request::currentURI( ['id' => $id], ['action'] );
			} );

		$this->addAction( 'view', $actions_map['view'] ?? '' )
			->setResolver( function() use ( $item_catcher, $action, $id ) {
				if( $action != '' ) {
					return false;
				}
				return $item_catcher( $id );
			} )
			->setURICreator( function( $id ) {
				return Http_Request::currentURI( ['id' => $id], ['action'] );
			} );

		$this->addAction( 'delete', $actions_map['delete'] ?? '' )
			->setResolver( function() use ( $item_catcher, $action, $id ) {
				if( $action != 'delete' ) {
					return false;
				}
				return $item_catcher( $id );
			} )
			->setURICreator( function( $id ) {
				return Http_Request::currentURI( [
					'id' => $id,
					'action' => 'delete'
				] );
			} );


	}

}