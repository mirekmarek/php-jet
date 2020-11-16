<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;


use Jet\Form;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Select;
use Jet\DataModel_Query;
use Jet\Tr;
use Jet\DataModel_Definition_Relation_External as Jet_DataModel_Definition_Relation_External;

/**
 *
 */
class DataModel_Definition_Relation_External extends Jet_DataModel_Definition_Relation_External {


	/**
	 * @var Form
	 */
	protected $_edit_form;

	/**
	 * @return string
	 */
	public function getInternalId()
	{
		return md5($this->this_data_model_class_name.'<'.$this->join_type.'>'.$this->related_data_model_class_name);
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public function getRelatedDataModel()
	{
		return DataModels::getClass($this->getRelatedDataModelClassName())->getDefinition();
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
		$classes = [''=>''];

		$current = DataModels::getCurrentClass();

		foreach( DataModels::getClasses() as $class ) {
			if(
				$class->getFullClassName()==$current->getFullClassName() ||
				($class instanceof DataModel_Definition_Model_Related_Interface)
			) {
				continue;
			}

			if( $current instanceof DataModel_Definition_Model_Related_Interface ) {
				if(
					$current->getParentModelClassName()==$class->getFullClassName() ||
					$current->getMainModelClassName()==$class->getFullClassName()
				) {
					continue;
				}
			}

			$classes[$class->getFullClassName()] = $class->getDefinition()->getModelName().' ('.$class->getFullClassName().')';
		}

		$select_field = new Form_Field_Select('related_model', 'Select related DataModel:');
		$select_field->setSelectOptions( $classes );
		$select_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select related model'
		]);

		$form = new Form('add_relation_select_related_model_form', [
			$select_field
		]);

		return $form;
	}

	/**
	 * @param DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN $related_model
	 *
	 * @return Form
	 */
	public static function getCreateForm( $related_model )
	{
		$model = DataModels::getCurrentModel();

		$fields = [];

		$related_model_class_name = new Form_Field_Hidden('related_model_class_name', '', $related_model->getClassName());
		$fields[] = $related_model_class_name;

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

		$form = new Form('create_external_relation_form', $fields);

		$form->setAction( DataModels::getActionUrl('relation/add') );

		return $form;
	}

	/**
	 * @param DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN $related_model
	 * @param Form $form
	 *
	 * @return bool|DataModel_Definition_Relation_External
	 */
	public static function catchCreateForm( $related_model, Form $form ) {
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$model = DataModels::getCurrentModel();


		$r = new DataModel_Definition_Relation_External();
		$r->setRelatedToClass( $related_model->getClassName() );
		$r->setJoinType( $form->getField('join_type')->getValue() );

		$join_by = [];
		foreach( $related_model->getProperties() as $property ) {
			if(!$property->getIsId()) {
				continue;
			}

			$join_by[$form->field('glue_'.$property->getInternalId())->getValue()] = $property->getInternalId();
		}

		$r->setJoinBy( $join_by );


		$model->addExternalRelation( $r );

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

			foreach( $related_model->getProperties() as $related_property ) {
				$options = [
					'' => Tr::_('- none -')
				];
				foreach( $model->getProperties() as $cm_property ) {
					if($cm_property->getType()!=$related_property->getType()) {
						continue;
					}
					$options[$cm_property->getName()] = $model->getModelName().'.'.$cm_property->getName();
				}

				if(count($options)<2) {
					continue;
				}

				$selected_property = '';

				foreach( $this->getJoinBy() as $j_i ) {

					if($j_i->getRelatedPropertyName()==$related_property->getName()) {
						$selected_property = $j_i->getThisPropertyName();
						break;
					}
				}

				$glue = new Form_Field_Select('glue_'.$related_property->getName(), $related_model->getModelName().'.'.$related_property->getName().' < - > ', $selected_property);
				$glue->setErrorMessages([
					Form_Field_Select::ERROR_CODE_EMPTY => 'Please select related property',
					Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select related property'
				]);


				$glue->setSelectOptions( $options );

				$fields[] = $glue;

			}

			$form = new Form('edit_external_relation_form_'.$this->getName(), $fields);

			$form->setAction( DataModels::getActionUrl('relation/edit') );

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

		$related_model = $this->getRelatedDataModel();


		$this->setJoinType( $form->getField('join_type')->getValue() );

		$join_by = [];
		foreach( $related_model->getProperties() as $related_property ) {
			$field_name = 'glue_'.$related_property->getName();
			if(!$form->fieldExists($field_name)) {
				continue;
			}

			$this_property_name = $form->field($field_name)->getValue();
			if(!$this_property_name) {
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
	 * @return ClassCreator_Annotation
	 */
	public function createClass_getAsAnnotation( ClassCreator_Class $class )
	{

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Query') );

		$glue = [];
		$this_model = DataModels::getCurrentModel();
		$related_model = $this->getRelatedDataModel();

		foreach( $this->getJoinBy() as $join_item ) {

			$this_property_id = $join_item->getThisPropertyName();
			$related_property_id = $join_item->getRelatedPropertyName();

			if(!$this_model->hasProperty( $this_property_id )) {
				$class->addWarning( Tr::_('Unknown relation property %property%', [
					'property' => $this_property_id
				]) );
				continue;
			}

			if(!$related_model->hasProperty( $related_property_id )) {
				$class->addWarning( Tr::_('Unknown relation property %property%', [
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



		$related_class = $this->getRelatedDataModel()->getClass();


		if( $related_class->getNamespace()!=DataModels::getCurrentClass()->getNamespace() ) {
			$ns = DataModels::getCurrentClass()->getNamespace();

			$class->addUse( new ClassCreator_UseClass($ns, $related_class->getClassName()) );
		}


		$r_data = [
			var_export($related_class->getClassName(), true),
			$glue,
			$type
		];

		$an = new ClassCreator_Annotation('JetDataModel', 'relation', $r_data);

		return $an;
	}
}