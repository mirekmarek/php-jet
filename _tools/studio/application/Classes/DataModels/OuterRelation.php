<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;


use Jet\DataModel_Definition_Relation;
use Jet\Form;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Select;
use Jet\DataModel_Query;
use Jet\DataModel_Definition_Relation_Join_Item;
use Jet\Tr;

class DataModels_OuterRelation extends DataModel_Definition_Relation {

	/**
	 * @var string
	 */
	protected $internal_id = '';

	/**
	 * @var Form
	 */
	protected $_edit_form;

	/**
	 *
	 */
	public function __construct()
	{
		$this->internal_id = uniqid();
	}

	/**
	 * @return string
	 */
	public function getInternalId()
	{
		return $this->internal_id;
	}

	/**
	 * @return DataModels_Model|DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN|null
	 */
	public function getRelatedDataModel()
	{
		return DataModels::getModel($this->getRelatedDataModelClassName());
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getRelatedDataModel()->getModelName();
	}

	/**
	 * @return Form
	 */
	public static function getSelectRelatedModelForm()
	{
		$models = [''=>''];

		$current = DataModels::getCurrentModel();

		foreach( DataModels::getModels() as $model ) {
			if(
				$model->getInternalId()==$current->getInternalId() ||
				($model instanceof DataModels_Model_Related_Interface)
			) {
				continue;
			}

			if( $current instanceof DataModels_Model_Related_Interface ) {
				if(
					$current->getInternalParentModelId()==$model->getInternalId() ||
					$current->getInternalMainModelId()==$model->getInternalId()
				) {
					continue;
				}
			}

			$models[$model->getInternalId()] = $model->getModelName().' ('.$model->getClassName().')';
		}

		$select_field = new Form_Field_Select('related_model', 'Select related DataModel:');
		$select_field->setSelectOptions( $models );
		$select_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select related model'
		]);

		$form = new Form('add_relation_select_related_model_form', [
			$select_field
		]);

		return $form;
	}

	/**
	 * @param DataModels_Model|DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN $related_model
	 *
	 * @return Form
	 */
	public static function getCreateForm( $related_model )
	{
		$model = DataModels::getCurrentModel();

		$fields = [];

		$related_model_id = new Form_Field_Hidden('related_model_id', '', $related_model->getInternalId());
		$fields[] = $related_model_id;

		$relation_type = new Form_Field_Select('join_type', 'Relation type: ', '');
		$relation_type->setSelectOptions([
			DataModel_Query::JOIN_TYPE_LEFT_JOIN => 'LEFT JOIN',
			DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN => 'LEFT OUTER JOIN'

		]);
		$relation_type->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select relation type',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select relation type'
		]);

		$fields[] = $relation_type;

		foreach( $related_model->getProperties() as $property ) {
			if(!$property->getIsId()) {
				continue;
			}

			$glue = new Form_Field_Select('glue_'.$property->getInternalId(), $property->getName().' < - > ', '', true);
			$glue->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please select related property',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select related property'
			]);

			$options = [];
			foreach( $model->getProperties() as $cm_property ) {
				if($cm_property->getType()!=$property->getType()) {
					continue;
				}
				$options[$cm_property->getInternalId()] = $cm_property->getName();
			}

			$glue->setSelectOptions( $options );

			$fields[] = $glue;

		}

		$form = new Form('create_outer_relation_form', $fields);

		$form->setAction( DataModels::getActionUrl('relation/add', [
			'related_model' => $related_model->getInternalId()
		]) );

		return $form;
	}

	/**
	 * @param DataModels_Model|DataModels_Model_Related_1to1|DataModels_Model_Related_1toN|DataModels_Model_Related_MtoN $related_model
	 * @param Form $form
	 *
	 * @return bool|DataModels_OuterRelation
	 */
	public static function catchCreateForm( $related_model, Form $form ) {
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$model = DataModels::getCurrentModel();


		$r = new DataModels_OuterRelation();
		$r->setRelatedToClass( $related_model->getInternalId() );
		$r->setJoinType( $form->getField('join_type')->getValue() );

		$join_by = [];
		foreach( $related_model->getProperties() as $property ) {
			if(!$property->getIsId()) {
				continue;
			}

			$join_by[$form->field('glue_'.$property->getInternalId())->getValue()] = $property->getInternalId();
		}

		$r->setJoinBy( $join_by );


		$model->addOuterRelation( $r );

		return $r;
	}

	/**
	 *
	 * @return Form
	 */
	public function getEditForm()
	{
		if(!$this->_edit_form) {
			$fields = [];

			$model = DataModels::getCurrentModel();
			$related_model = $this->getRelatedDataModel();


			$relation_type = new Form_Field_Select('join_type', 'Relation type: ', $this->getJoinType());
			$relation_type->setSelectOptions([
				DataModel_Query::JOIN_TYPE_LEFT_JOIN => 'LEFT JOIN',
				DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN => 'LEFT OUTER JOIN'

			]);
			$relation_type->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please select relation type',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select relation type'
			]);

			$fields[] = $relation_type;

			foreach( $related_model->getProperties() as $property ) {
				if(!$property->getIsId()) {
					continue;
				}

				$selected_property = '';

				foreach( $this->getJoinBy() as $j_i ) {
					if($j_i->getRelatedPropertyName()==$property->getInternalId()) {
						$selected_property = $j_i->getThisPropertyName();
						break;
					}
				}

				$glue = new Form_Field_Select('glue_'.$property->getInternalId(), $related_model->getModelName().'.'.$property->getName().' < - > ', $selected_property, true);
				$glue->setErrorMessages([
					Form_Field_Select::ERROR_CODE_EMPTY => 'Please select related property',
					Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select related property'
				]);

				$options = [];
				foreach( $model->getProperties() as $cm_property ) {
					if($cm_property->getType()!=$property->getType()) {
						continue;
					}
					$options[$cm_property->getInternalId()] = $model->getModelName().'.'.$cm_property->getName();
				}

				$glue->setSelectOptions( $options );

				$fields[] = $glue;

			}

			$form = new Form('edit_outer_relation_form_'.$this->getInternalId(), $fields);

			$form->setAction( DataModels::getActionUrl('relation/edit', [
				'relation' => $this->getInternalId()
			]) );

			$this->_edit_form = $form;
		}

		return $this->_edit_form;

	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$model = DataModels::getCurrentModel();
		$related_model = $this->getRelatedDataModel();


		$this->setJoinType( $form->getField('join_type')->getValue() );

		$join_by = [];
		foreach( $related_model->getProperties() as $property ) {
			if(!$property->getIsId()) {
				continue;
			}


			$join_by[$form->field('glue_'.$property->getInternalId())->getValue()] = $property->getInternalId();
		}

		$this->setJoinBy( $join_by );

		return true;
	}


	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Annotation
	 */
	public function getAsAnnotation( ClassCreator_Class $class )
	{

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Query') );

		$glue = [];
		$this_model = DataModels::getCurrentModel();
		$related_model = $this->getRelatedDataModel();

		foreach( $this->getJoinBy() as $join_item ) {

			$this_property_id = $join_item->getThisPropertyName();
			$related_property_id = $join_item->getRelatedPropertyName();

			if(!$this_model->hasProperty( $this_property_id )) {
				$class->addWarning( Tr::_('Unknown outer relation property %property%', [
					'property' => $this_property_id
				]) );
				continue;
			}

			if(!$related_model->hasProperty( $related_property_id )) {
				$class->addWarning( Tr::_('Unknown outer relation property %property%', [
					'property' => $related_property_id
				]) );
				continue;
			}

			$this_property = $this_model->getProperty( $this_property_id );
			$related_property = $related_model->getProperty( $related_property_id );

			$glue[$this_property->getName()] = var_export( $related_property->getName(), true );
		}

		$type = $this->getJoinType();

		switch( $this->getJoinType() ) {
			case DataModel_Query::JOIN_TYPE_LEFT_JOIN:
				$type = 'DataModel_Query::JOIN_TYPE_LEFT_JOIN';
			break;
			case DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN:
				$type = 'DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN';
			break;
		}



		$class_name = $this->getRelatedDataModel()->getClassName();

		if( $this->getRelatedDataModel()->getNamespaceId()!=Project::getCurrentNamespaceId() ) {
			$ns = Project::getNamespace( $this->getRelatedDataModel()->getNamespaceId() );

			$class->addUse( new ClassCreator_UseClass($ns->getNamespace(), $class_name) );
		}

		$r_data = [
			var_export($class_name, true),
			$glue,
			$type
		];

		$an = new ClassCreator_Annotation('JetDataModel', 'relation', $r_data);

		return $an;
	}
}