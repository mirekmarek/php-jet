<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

interface DataModel_Related_Item_Interface extends DataModel_Related_Interface{

	/**
	 * @return DataModel_Related_Interface
	 */
	public function createNewRelatedDataModelInstance();

	/**
	 *
	 * @param DataModel_Id_Abstract $main_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 * @return array
	 */
	public static function loadRelatedData(DataModel_Id_Abstract $main_id, DataModel_PropertyFilter $load_filter=null );

	/**
	 *
	 * @param array &$loaded_related_data
	 * @param DataModel_Id_Abstract|null $parent_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return mixed
	 */
	public static function loadRelatedInstances(array &$loaded_related_data, DataModel_Id_Abstract $parent_id=null, DataModel_PropertyFilter $load_filter=null );


}