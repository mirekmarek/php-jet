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
trait DataModel_Trait_Save
{
	/**
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save()
	{
		/**
		 * @var DataModel $this
		 */

		if( $this->getLoadFilter() ) {
			throw new DataModel_Exception(
				'Nothing to save... Object is not completely loaded. (Class: \''.get_class(
					$this
				).'\', ID:\''.$this->getIdObject().'\')'
			);
		}

		$this->beforeSave();


		$this->startBackendTransaction();


		if( $this->getIsNew() ) {
			$after_method_name = 'afterAdd';
			$operation = 'save';
		} else {
			$after_method_name = 'afterUpdate';
			$operation = 'update';
		}

		$this->{'_'.$operation}();

		$this->commitBackendTransaction();


		$this->setIsSaved();

		$this->{$after_method_name}();
	}

	/**
	 * @param array $data
	 * @param array $where
	 */
	public function updateData( array $data, array $where )
	{
		/**
		 * @var DataModel         $this
		 * @var DataModel_Backend $backend
		 */

		$backend = static::getBackendInstance();

		$backend->update(
			DataModel_RecordData::createRecordData(
				$this, $data
			),
			static::createQuery(
				$where
			)
		);

	}

	/**
	 *
	 */
	protected function _save()
	{

		/**
		 * @var DataModel                  $this
		 * @var DataModel_Definition_Model $definition
		 */
		$definition = static::getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		$id = $this->getIdObject();

		$id->generate();
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

		$id->afterSave( $backend_result );

		/**
		 * @var DataModel_Trait_Save $this
		 */
		$this->_saveRelatedObjects();

	}

	/**
	 *
	 */
	protected function _saveRelatedObjects()
	{

		/**
		 * @var DataModel                  $this
		 * @var DataModel_Definition_Model $definition
		 *
		 */
		$definition = static::getDataModelDefinition();

		$is_related = ( $this instanceof DataModel_Related_Interface );
		foreach( $definition->getProperties() as $property_name => $property_definition ) {

			/**
			 * @var DataModel_Related_Interface $prop
			 */
			$prop = $this->{$property_name};
			if( !( $prop instanceof DataModel_Related_Interface ) ) {
				continue;
			}

			if( $is_related ) {
				$prop->actualizeParentId( $this->getIdObject() );
			} else {
				$prop->actualizeMainId( $this->getIdObject() );
			}

			$prop->save();
		}
	}

	/**
	 *
	 * @throws DataModel_Exception
	 */
	protected function _update()
	{
		/**
		 * @var DataModel                  $this
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
			$where_query = $this->getIdObject()->getQuery();
			if( $where_query->getWhere()->getIsEmpty() ) {
				throw  new DataModel_Exception( 'Empty WHERE!' );
			}

			/**
			 * @var DataModel_Backend $backend
			 */
			$backend = static::getBackendInstance();

			$backend->update( $record, $where_query );
		}

		/**
		 * @var DataModel_Trait_Save $this
		 */
		$this->_saveRelatedObjects();

	}
}