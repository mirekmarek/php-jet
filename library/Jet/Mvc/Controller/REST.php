<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @return string
	 */
	public function getRequestMethod(): string
	{
		return REST::getRequestMethod();
	}

	/**
	 * @return array
	 */
	public function getRequestData(): array
	{
		return REST::getRequestData();
	}


	/**
	 * @param string $message
	 */
	public function responseOK( string $message = '' ): void
	{
		REST::responseOK( $message );
	}

	/**
	 * @param mixed $data
	 */
	public function responseData( mixed $data ): void
	{
		REST::responseData( $data );
	}


	/**
	 * @param string|int $code
	 * @param mixed $data
	 *
	 * @throws Mvc_Controller_Exception
	 */
	public function responseError( string|int $code, mixed $data = null ): void
	{
		REST::responseError( $code, $data );
	}

	/**
	 *
	 */
	public function handleNotAuthorized(): void
	{
		REST::handleNotAuthorized();
	}

	/**
	 * @param array $errors
	 */
	public function responseValidationError( array $errors ): void
	{
		REST::responseValidationError( $errors );
	}


	/**
	 * @param string|array $id
	 */
	public function responseUnknownItem( string|array $id ): void
	{
		REST::responseUnknownItem( $id );
	}

	/**
	 *
	 */
	public function responseBadRequest(): void
	{
		REST::responseBadRequest();
	}


	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 *
	 * @return Data_Paginator
	 */
	protected function handleDataPagination( DataModel_Fetch_Instances $data ): Data_Paginator
	{
		return REST::handleDataPagination( $data );
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
		return REST::handleOrderBy( $data, $sort_items_map );
	}

}