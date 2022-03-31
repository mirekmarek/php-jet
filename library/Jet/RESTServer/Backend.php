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
interface RESTServer_Backend
{

	/**
	 *
	 */
	public function init(): void;
	
	/**
	 * @return string
	 */
	public function getRequestMethod(): string;
	
	/**
	 * @return array
	 */
	public function getRequestData(): array;
	
	/**
	 *
	 * @param string $header
	 * @param string $default_value
	 *
	 * @return string
	 */
	public function getHttpRequestHeader( string $header, string $default_value = '' ): string;
	
	
	/**
	 * @param string $message
	 */
	public function responseOK( string $message = '' ): void;
	
	/**
	 * @param mixed $data
	 */
	public function responseData( mixed $data ): void;
	
	
	/**
	 * @param string|int $code
	 * @param mixed $data
	 *
	 * @throws MVC_Controller_Exception
	 */
	public function responseError( string|int $code, mixed $data = null ): void;
	
	
	/**
	 *
	 */
	public function handleNotAuthorized(): void;
	
	/**
	 * @param array $errors
	 */
	public function responseValidationError( array $errors ): void;
	
	
	/**
	 * @param string|array $id
	 */
	public function responseUnknownItem( string|array $id ): void;
	
	/**
	 *
	 */
	public function responseBadRequest(): void;
	
	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 *
	 * @return Data_Paginator
	 */
	public function handleDataPagination( DataModel_Fetch_Instances $data ): Data_Paginator;
	
	/**
	 *
	 * @param DataModel_Fetch_Instances $data
	 * @param array $sort_items_map
	 *
	 * @return DataModel_Fetch_Instances
	 */
	public function handleOrderBy( DataModel_Fetch_Instances $data, array $sort_items_map ): DataModel_Fetch_Instances;
	
}