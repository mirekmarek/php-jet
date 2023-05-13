<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\DataListing\Filter\DataListing_Filter_OptionSelect;
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
		$field->getSelectOptions()[Logger::EVENT_CLASS_SUCCESS]->setSelectOptionCssClass('text-success');
		$field->getSelectOptions()[Logger::EVENT_CLASS_INFO]->setSelectOptionCssClass('text-info');
		$field->getSelectOptions()[Logger::EVENT_CLASS_WARNING]->setSelectOptionCssClass('text-warning');
		$field->getSelectOptions()[Logger::EVENT_CLASS_DANGER]->setSelectOptionCssClass('text-danger');
		$field->getSelectOptions()[Logger::EVENT_CLASS_FAULT]->setSelectOptionCssClass('text-danger');
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