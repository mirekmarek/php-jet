<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;
use Jet\Tr;
use Jet\Locale;

class UI
{
    const DEFAULT_ICON_CLASS = 'glyphicon glyphicon-';


    /**
     * @param string $label
     *
     * @return UI_button_save
     */
    public static function button_save($label='' ) {

    	if(!$label) {
		    $label = Tr::_( 'Save', [], Tr::COMMON_NAMESPACE );
	    }

        $button = new UI_button_save( $label );

        return $button;

    }

	/**
	 * @param string $label
	 * @return UI_button_goBack
	 */
	public static function button_goBack( $label='' ) {

		if(!$label) {
			$label = Tr::_( 'Back to the list', [], Tr::COMMON_NAMESPACE );
		}

		$button = new UI_button_goBack( $label );

		return $button;

	}

	/**
	 * @param string $label
	 * @return UI_button_edit
	 */
	public static function button_edit( $label='' ) {

		if(!$label) {
			$label = Tr::_( 'Edit', [], Tr::COMMON_NAMESPACE );
		}

		$button = new UI_button_edit( $label );

		return $button;

	}

	/**
	 * @param string $label
	 * @return UI_button_delete
	 */
	public static function button_delete( $label='' ) {

		if(!$label) {
			$label = Tr::_( 'Delete', [], Tr::COMMON_NAMESPACE );
		}

		$button = new UI_button_delete( $label );

		return $button;

	}


	/**
	 * @param string $label
	 * @return UI_button_create
	 */
	public static function button_create( $label ) {

		$button = new UI_button_create( $label );

		return $button;

	}

	/**
	 * @param Locale $locale
	 *
	 * @return UI_flag
	 */
	public static function flag( Locale $locale ) {

		$flag = new UI_flag( $locale );

		return $flag;
	}

	/**
	 * @param string $icon
	 *
	 * @return UI_icon
	 */
	public static function icon( $icon ) {
		$icon = new UI_icon( $icon );

		return $icon;
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @param int $width
	 *
	 * @return UI_dialog
	 */
	public static function dialog( $id, $title, $width) {

		$dialog = new UI_dialog( $id, $title, $width );

		return $dialog;

	}

}