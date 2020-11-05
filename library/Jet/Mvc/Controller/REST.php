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
abstract class Mvc_Controller_REST extends Mvc_Controller
{
	
	/**
	 *
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function __construct( Mvc_Page_Content_Interface $content )
	{
		parent::__construct( $content );

		REST::init();
	}

	/**
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	public function resolve()
	{
		throw new Exception('You have to implement resolver: '.get_called_class().'::resolve() ');

		/** @noinspection PhpUnreachableStatementInspection */
		return false;
	}

	/**
	 * @return string
	 */
	public function getRequestMethod() {
		return REST::getRequestMethod();
	}

	/**
	 * @return bool|mixed
	 */
	public function getRequestData()
	{
		return REST::getRequestData();
	}


	/**
	 * @param string $message
	 */
	public function responseOK( $message='' )
	{
		REST::responseOK( $message );
	}

	/**
	 * @param mixed $data
	 */
	public function responseData(  $data )
	{
		REST::responseData( $data );
	}


	/**
	 * @param string|int $code
	 * @param mixed      $data
	 *
	 * @throws Mvc_Controller_Exception
	 */
	public function responseError( $code, $data = null )
	{
		REST::responseError( $code, $data );
	}
	
	/**
	 *
	 */
	public function responseAccessDenied()
	{
		REST::responseAccessDenied();
	}

	/**
	 * @param array $errors
	 */
	public function responseValidationError( array $errors )
	{
		REST::responseValidationError( $errors );
	}


	/**
	 * @param string|array $id
	 */
	public function responseUnknownItem( $id )
	{
		REST::responseUnknownItem( $id );
	}

	/**
	 *
	 */
	public function responseBadRequest()
	{
		REST::responseBadRequest();
	}
	
	
	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 *
	 * @return Data_Paginator
	 */
	protected function handleDataPagination( DataModel_Fetch_Instances $data )
	{
		return REST::handleDataPagination( $data );
	}

	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 * @param array           $sort_items_map
	 *
	 * @return DataModel_Fetch_Instances
	 */
	protected function handleOrderBy( DataModel_Fetch_Instances $data, array $sort_items_map )
	{
		return REST::handleOrderBy( $data, $sort_items_map );
	}

}