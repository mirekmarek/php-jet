<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Fetch
 */
namespace Jet;

class DataModel_Fetch_Data_Col extends DataModel_Fetch_Data_Abstract {
	/**
	 * @var string
	 */
	protected $backend_fetch_method = 'fetchCol';

	/**
	 *
	 * @param string $select_item
	 * @param DataModel_Query $query
	 *
	 */
	public function __construct($select_item, DataModel_Query $query ) {
		parent::__construct( [$select_item], $query );
	}

}