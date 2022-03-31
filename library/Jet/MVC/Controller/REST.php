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
abstract class MVC_Controller_REST extends MVC_Controller
{

	/**
	 *
	 * @param MVC_Page_Content_Interface $content
	 */
	public function __construct( MVC_Page_Content_Interface $content )
	{
		parent::__construct( $content );

		RESTServer::init();
	}

	/**
	 * @return string
	 */
	public function getRequestMethod(): string
	{
		return RESTServer::getRequestMethod();
	}

	/**
	 * @return array
	 */
	public function getRequestData(): array
	{
		return RESTServer::getRequestData();
	}


	/**
	 * @param string $message
	 */
	public function responseOK( string $message = '' ): void
	{
		RESTServer::responseOK( $message );
	}

	/**
	 * @param mixed $data
	 */
	public function responseData( mixed $data ): void
	{
		RESTServer::responseData( $data );
	}


	/**
	 * @param string|int $code
	 * @param mixed $data
	 */
	public function responseError( string|int $code, mixed $data = null ): void
	{
		RESTServer::responseError( $code, $data );
	}

	/**
	 *
	 */
	public function handleNotAuthorized(): void
	{
		RESTServer::handleNotAuthorized();
	}

	/**
	 * @param array $errors
	 */
	public function responseValidationError( array $errors ): void
	{
		RESTServer::responseValidationError( $errors );
	}


	/**
	 * @param string|array $id
	 */
	public function responseUnknownItem( string|array $id ): void
	{
		RESTServer::responseUnknownItem( $id );
	}

	/**
	 *
	 */
	public function responseBadRequest(): void
	{
		RESTServer::responseBadRequest();
	}


	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 *
	 * @return Data_Paginator
	 */
	protected function handleDataPagination( DataModel_Fetch_Instances $data ): Data_Paginator
	{
		return RESTServer::handleDataPagination( $data );
	}

	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 * @param array $sort_items_map
	 *
	 * @return DataModel_Fetch_Instances
	 */
	protected function handleOrderBy( DataModel_Fetch_Instances $data, array $sort_items_map ): DataModel_Fetch_Instances
	{
		return RESTServer::handleOrderBy( $data, $sort_items_map );
	}

}