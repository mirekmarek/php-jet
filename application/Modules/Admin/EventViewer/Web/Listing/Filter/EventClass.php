<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\EventViewer\Web;

use Jet\DataListing_Filter_OptionSelect;
use Jet\Form_Field_Select;
use Jet\Logger;
use Jet\Tr;

/**
 *
 */
class Listing_Filter_EventClass extends DataListing_Filter_OptionSelect {
	
	public const KEY = 'event_class';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getParamName() : string
	{
		return 'event_class';
	}
	
	public function getFormFieldLabel() : string
	{
		return 'Event class:';
	}
	
	
	protected function setFieldSelectOptions( Form_Field_Select $field ) : void
	{
		$options = [
			''                          => Tr::_( '- all -' ),
			Logger::EVENT_CLASS_SUCCESS => Tr::_( 'success' ),
			Logger::EVENT_CLASS_INFO    => Tr::_( 'info' ),
			Logger::EVENT_CLASS_WARNING => Tr::_( 'warning' ),
			Logger::EVENT_CLASS_DANGER  => Tr::_( 'danger' ),
			Logger::EVENT_CLASS_FAULT   => Tr::_( 'fault' ),
		];
		
		$field->setSelectOptions( $options );
		
		$options = $field->getSelectOptions();
		$options[Logger::EVENT_CLASS_SUCCESS]->setSelectOptionCssClass('text-success');
		$options[Logger::EVENT_CLASS_INFO]->setSelectOptionCssClass('text-info');
		$options[Logger::EVENT_CLASS_WARNING]->setSelectOptionCssClass('text-warning');
		$options[Logger::EVENT_CLASS_DANGER]->setSelectOptionCssClass('text-danger');
		$options[Logger::EVENT_CLASS_FAULT]->setSelectOptionCssClass('text-danger');
	}
	
	public function generateWhere(): void
	{
		if( $this->selected_value ) {
			$this->listing->addFilterWhere( [
				'event_class' => $this->selected_value,
			] );
		}
	}
	
}