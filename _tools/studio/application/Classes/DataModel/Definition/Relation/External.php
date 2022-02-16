<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;


use Jet\DataModel;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Select;
use Jet\DataModel_Query;
use Jet\Tr;
use Jet\DataModel_Definition_Relation_External as Jet_DataModel_Definition_Relation_External;
use Jet\UI_messages;

/**
 *
 */
class DataModel_Definition_Relation_External extends Jet_DataModel_Definition_Relation_External
{


	/**
	 * @var ?Form
	 */
	protected ?Form $_edit_form = null;

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getRelatedDataModel(): DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	{
		return DataModels::getClass( $this->getRelatedDataModelClassName() )->getDefinition();
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->getRelatedDataModel()->getModelName();
	}

	/**
	 * @return Form
	 */
	public static function getSelectRelatedModelForm(): Form
	{
		$classes = ['' => ''];

		$current = DataModels::getCurrentClass();

		$exists = array_keys( $current->getDefinition()->getRelations() );

		foreach( DataModels::getClasses() as $class ) {
			if(
				$class->getFullClassName() == $current->getFullClassName() ||
				($class instanceof DataModel_Definition_Model_Related_Interface)
			) {
				continue;
			}

			if( $current instanceof DataModel_Definition_Model_Related_Interface ) {
				if(
					$current->getParentModelClassName() == $class->getFullClassName() ||
					$current->getMainModelClassName() == $class->getFullClassName()
				) {
					continue;
				}
			}

			if( in_array( $class->getDefinition()->getModelName(), $exists ) ) {
				continue;
			}

			$classes[$class->getFullClassName()] = $class->getDefinition()->getModelName() . ' (' . $class->getFullClassName() . ')';
		}

		asort( $classes );

		$select_field = new Form_Field_Select( 'related_model', 'Select related DataModel:' );
		$select_field->setSelectOptions( $classes );
		$select_field->setErrorMessages( [
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select related model'
		] );

		return new Form( 'add_relation_select_related_model_form', [
			$select_field
		] );

	}

	/**
	 * @param DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $related_model
	 *
	 * @return Form
	 */
	public static function getCreateForm( DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $related_model ): Form
	{

		$model = DataModels::getCurrentModel();

		$fields = [];

		$related_model_class_name = new Form_Field_Hidden( 'related_model_class_name', '' );
		$related_model_class_name->setDefaultValue( $related_model->getClassName() );
		$fields[] = $related_model_class_name;

		$relation_type = new Form_Field_Select( 'join_type', 'Relation type: ' );
		$relation_type->setSelectOptions( [
			DataModel_Query::JOIN_TYPE_LEFT_JOIN       => 'LEFT JOIN',
			DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN => 'LEFT OUTER JOIN'

		] );
		$relation_type->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select relation type',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select relation type'
		] );

		$fields[] = $relation_type;


		foreach( $related_model->getProperties() as $related_property ) {
			if(
			in_array( $related_property->getType(), [
				DataModel::TYPE_CUSTOM_DATA,
				DataModel::TYPE_DATA_MODEL
			] )
			) {
				continue;
			}

			$options = [
				'' => Tr::_( '- none -' )
			];

			$related_property_type = $related_property->getType();
			if($related_property_type==DataModel::TYPE_ID_AUTOINCREMENT) {
				$related_property_type=DataModel::TYPE_INT;
			}

			foreach( $model->getProperties() as $cm_property ) {
				$cm_property_type = $cm_property->getType();

				if($cm_property_type==DataModel::TYPE_ID_AUTOINCREMENT) {
					$cm_property_type=DataModel::TYPE_INT;
				}

				if( $cm_property_type != $related_property_type ) {
					continue;
				}
				$options[$cm_property->getName()] = $model->getModelName() . '.' . $cm_property->getName();
			}

			if( count( $options ) < 2 ) {
				continue;
			}

			$selected_property = '';

			$glue = new Form_Field_Select( 'glue_' . $related_property->getName(), $related_model->getModelName() . '.' . $related_property->getName() . ' < - > ' );
			$glue->setDefaultValue( $selected_property );
			$glue->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select related property',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select related property'
			] );


			$glue->setSelectOptions( $options );

			$fields[] = $glue;

		}


		$form = new Form( 'create_external_relation_form', $fields );

		$form->setAction( DataModels::getActionUrl( 'relation/add' ) );

		return $form;
	}

	/**
	 * @param DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $related_model
	 * @param Form $form
	 *
	 * @return bool|DataModel_Definition_Relation_External
	 */
	public static function catchCreateForm( DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $related_model, Form $form ): bool|DataModel_Definition_Relation_External
	{
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$model = DataModels::getCurrentModel();


		$r = new DataModel_Definition_Relation_External();
		$r->setRelatedToClass( $related_model->getClassName() );
		$r->setJoinType( $form->getField( 'join_type' )->getValue() );

		$join_by = [];
		foreach( $related_model->getProperties() as $related_property ) {
			$field_name = 'glue_' . $related_property->getName();
			if( !$form->fieldExists( $field_name ) ) {
				continue;
			}

			$this_property_name = $form->field( $field_name )->getValue();
			if( !$this_property_name ) {
				continue;
			}

			$join_by[$this_property_name] = $related_property->getName();
		}

		if( !$join_by ) {
			$form->setCommonMessage(
				UI_messages::createDanger(
					Tr::_( 'It is necessary to specify at least one relation' )
				)
			);

			return false;
		}

		$r->setJoinBy( $join_by );


		$model->addExternalRelation( $r );

		return $r;
	}

	/**
	 *
	 * @return Form
	 */
	public function getEditForm(): Form
	{
		if( !$this->_edit_form ) {
			$fields = [];

			$model = DataModels::getCurrentModel();
			$related_model = $this->getRelatedDataModel();


			$relation_type = new Form_Field_Select( 'join_type', 'Relation type: ' );
			$relation_type->setDefaultValue( $this->getJoinType() );
			$relation_type->setSelectOptions( [
				DataModel_Query::JOIN_TYPE_LEFT_JOIN       => 'LEFT JOIN',
				DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN => 'LEFT OUTER JOIN'

			] );
			$relation_type->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select relation type',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select relation type'
			] );

			$fields[] = $relation_type;

			foreach( $related_model->getProperties() as $related_property ) {
				if(
				in_array( $related_property->getType(), [
					DataModel::TYPE_CUSTOM_DATA,
					DataModel::TYPE_DATA_MODEL
				] )
				) {
					continue;
				}

				$options = [
					'' => Tr::_( '- none -' )
				];

				$related_property_type = $related_property->getType();
				if($related_property_type==DataModel::TYPE_ID_AUTOINCREMENT) {
					$related_property_type=DataModel::TYPE_INT;
				}


				foreach( $model->getProperties() as $cm_property ) {
					$cm_property_type = $cm_property->getType();
					if($cm_property_type==DataModel::TYPE_ID_AUTOINCREMENT) {
						$cm_property_type = DataModel::TYPE_INT;
					}

					if( $cm_property_type != $related_property_type ) {
						continue;
					}
					$options[$cm_property->getName()] = $model->getModelName() . '.' . $cm_property->getName();
				}

				if( count( $options ) < 2 ) {
					continue;
				}

				$selected_property = '';

				foreach( $this->getJoinBy() as $j_i ) {

					if( $j_i->getRelatedPropertyName() == $related_property->getName() ) {
						$selected_property = $j_i->getThisPropertyName();
						break;
					}
				}

				$glue = new Form_Field_Select( 'glue_' . $related_property->getName(), $related_model->getModelName() . '.' . $related_property->getName() . ' < - > ' );
				$glue->setDefaultValue( $selected_property );
				$glue->setErrorMessages( [
					Form_Field::ERROR_CODE_EMPTY         => 'Please select related property',
					Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select related property'
				] );


				$glue->setSelectOptions( $options );

				$fields[] = $glue;

			}

			$form = new Form( 'edit_external_relation_form_' . $this->getName(), $fields );

			$form->setAction( DataModels::getActionUrl( 'relation/edit' ) );

			$this->_edit_form = $form;
		}

		return $this->_edit_form;

	}

	/**
	 * @return bool
	 */
	public function catchEditForm(): bool
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$related_model = $this->getRelatedDataModel();


		$this->setJoinType( $form->getField( 'join_type' )->getValue() );

		$join_by = [];
		foreach( $related_model->getProperties() as $related_property ) {
			$field_name = 'glue_' . $related_property->getName();
			if( !$form->fieldExists( $field_name ) ) {
				continue;
			}

			$this_property_name = $form->field( $field_name )->getValue();
			if( !$this_property_name ) {
				continue;
			}

			$join_by[$this_property_name] = $related_property->getName();
		}

		$this->setJoinBy( $join_by );

		return true;
	}


	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClass_getAsAttribute( ClassCreator_Class $class ): array
	{

		$class->addUse( new ClassCreator_UseClass( 'Jet', 'DataModel_Query' ) );

		$glue = [];
		$this_model = DataModels::getCurrentModel();
		$related_model = $this->getRelatedDataModel();

		foreach( $this->getJoinBy() as $join_item ) {

			$this_property_id = $join_item->getThisPropertyName();
			$related_property_id = $join_item->getRelatedPropertyName();

			if( !$this_model->hasProperty( $this_property_id ) ) {
				$class->addWarning( Tr::_( 'Unknown relation property %property%', [
					'property' => $this_property_id
				] ) );
				continue;
			}

			if( !$related_model->hasProperty( $related_property_id ) ) {
				$class->addWarning( Tr::_( 'Unknown relation property %property%', [
					'property' => $related_property_id
				] ) );
				continue;
			}

			$this_property = $this_model->getProperty( $this_property_id );
			$related_property = $related_model->getProperty( $related_property_id );

			$glue[$this_property->getName()] = $related_property->getName();
		}

		$type = match ($this->getJoinType()) {
			DataModel_Query::JOIN_TYPE_LEFT_JOIN => 'DataModel_Query::JOIN_TYPE_LEFT_JOIN',
			DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN => 'DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN',
		};


		$related_class = $this->getRelatedDataModel()->getClass();


		if( $related_class->getNamespace() != DataModels::getCurrentClass()->getNamespace() ) {
			$ns = $related_class->getNamespace();

			$class->addUse( new ClassCreator_UseClass( $ns, $related_class->getClassName() ) );
		}


		return [
			'related_to_class_name' => $related_class->getClassName() . '::class',
			'join_by_properties'    => $glue,
			'join_type'             => $type
		];


	}
}