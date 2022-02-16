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
trait DataModel_Trait_Save
{
	/**
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save(): void
	{
		if( $this->getLoadFilter() ) {
			throw new DataModel_Exception(
				'Nothing to save... Object is not completely loaded. (Class: \'' . get_class( $this ) . '\', ID:\'' . $this->getIDController() . '\')'
			);
		}

		$this->beforeSave();

		$this->startBackendTransaction();


		if( $this->getIsNew() ) {
			$this->_save();
			$this->commitBackendTransaction();
			$this->afterAdd();
			$this->setIsSaved();
		} else {
			$this->_update();
			$this->commitBackendTransaction();
			$this->afterUpdate();
		}
	}

	/**
	 * @param array $data
	 * @param array $where
	 */
	public static function updateData( array $data, array $where ): void
	{
		/**
		 * @var DataModel $this
		 * @var DataModel_Backend $backend
		 */

		$backend = static::getBackendInstance();

		$backend->update(
			DataModel_RecordData::createRecordData(
				static::class,
				$data
			),
			static::createQuery(
				$where
			)
		);

	}

	/**
	 *
	 */
	protected function _save(): void
	{

		/**
		 * @var DataModel $this
		 * @var DataModel_Definition_Model $definition
		 */
		$definition = static::getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		$id_controller = $this->getIDController();

		$id_controller->beforeSave();
		foreach( $definition->getProperties() as $property_name => $property_definition ) {
			if( !$property_definition->getCanBeInInsertRecord() ) {
				continue;
			}

			$record->addItem( $property_definition, $this->{$property_name} );
		}

		/**
		 * @var DataModel_Backend $backend
		 */
		$backend = static::getBackendInstance();

		$backend_result = $backend->save( $record );

		$id_controller->afterSave( $backend_result );

		$this->_saveRelatedObjects();

	}

	/**
	 *
	 * @throws DataModel_Exception
	 */
	protected function _update(): void
	{
		/**
		 * @var DataModel $this
		 * @var DataModel_Definition_Model $definition
		 */
		$definition = static::getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );


		foreach( $definition->getProperties() as $property_name => $property_definition ) {
			if( !$property_definition->getCanBeInUpdateRecord() ) {
				continue;
			}

			$record->addItem( $property_definition, $this->{$property_name} );
		}

		if( !$record->getIsEmpty() ) {
			$id_controller = $this->getIDController();
			$where_query = $id_controller->getQuery();
			if( $where_query->getWhere()->getIsEmpty() ) {
				throw  new DataModel_Exception( 'Empty WHERE!' );
			}

			/**
			 * @var DataModel_Backend $backend
			 */
			$backend = static::getBackendInstance();

			$backend->update( $record, $where_query );
		}

		$this->_saveRelatedObjects();
	}


	/**
	 *
	 */
	protected function _saveRelatedObjects(): void
	{

		/**
		 * @var DataModel $this
		 * @var DataModel_Definition_Model $definition
		 *
		 */
		$definition = static::getDataModelDefinition();

		$related_properties = [];
		foreach( $definition->getProperties() as $property_name => $property_definition ) {
			if(!($property_definition instanceof DataModel_Definition_Property_DataModel)) {
				continue;
			}

			$related_properties[] = $property_name;
		}


		if(!($this instanceof DataModel_Related)) {
			$main_id = $this->getIDController();
			$parent_id = null;
		} else {
			$main_id = null;
			$parent_id = $this->getIDController();
		}

		foreach( $related_properties as $property_name ) {
			$prop = $this->{$property_name};

			if(
				is_object($prop) &&
				$prop instanceof DataModel_Related
			) {
				$prop->actualizeRelations( $main_id, $parent_id );
			}

			if(is_array($prop)) {
				foreach($prop as $v) {
					if(
						is_object($v) &&
						$v instanceof DataModel_Related
					) {
						$v->actualizeRelations( $main_id, $parent_id );
					}
				}
			}
		}



		foreach( $related_properties as $property_name ) {

			$prop = $this->{$property_name};

			if(
				is_object($prop) &&
				$prop instanceof DataModel_Related
			) {
				$prop->save();
			}

			if(is_array($prop)) {
				foreach($prop as $v) {
					if(
						is_object($v) &&
						$v instanceof DataModel_Related
					) {
						$v->save();
					}
				}
			}
		}
	}

}