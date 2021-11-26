<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
interface DataModel_Related_Interface extends DataModel_Interface
{

	/**
	 *
	 * @param array $where
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array
	 */
	public static function fetchRelatedData( array $where, DataModel_PropertyFilter $load_filter = null ): array;

	/**
	 *
	 * @param array $this_data
	 * @param array  &$related_data
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return mixed
	 */
	public static function initRelatedByData( array $this_data, array &$related_data, DataModel_PropertyFilter $load_filter = null ): mixed;


	/**
	 * @param DataModel_IDController|null $main_id
	 * @param DataModel_IDController|null $parent_id
	 */
	public function actualizeRelations( ?DataModel_IDController $main_id=null, ?DataModel_IDController $parent_id=null );

	/**
	 *
	 */
	public function save(): void;

	/**
	 *
	 */
	public function delete(): void;

}