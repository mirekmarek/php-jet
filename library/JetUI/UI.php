<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetUI;
use Jet\Tr;
use Jet\Locale;

class UI
{
    const DEFAULT_ICON_CLASS = 'fa fa-';


    /**
     * @param string $label
     *
     * @return button_save
     */
    public static function button_save($label='' ) {

    	if(!$label) {
		    $label = Tr::_( 'Save', [], Tr::COMMON_NAMESPACE );
	    }

        $button = new button_save( $label );

        return $button;

    }

	/**
	 * @param string $label
	 * @return button_goBack
	 */
	public static function button_goBack( $label='' ) {

		if(!$label) {
			$label = Tr::_( 'Back to List', [], Tr::COMMON_NAMESPACE );
		}

		$button = new button_goBack( $label );

		return $button;

	}

	/**
	 * @param string $label
	 * @return button_edit
	 */
	public static function button_edit( $label='' ) {

		if(!$label) {
			$label = Tr::_( 'Edit', [], Tr::COMMON_NAMESPACE );
		}

		$button = new button_edit( $label );

		return $button;

	}

	/**
	 * @param string $label
	 * @return button_delete
	 */
	public static function button_delete( $label='' ) {

		if(!$label) {
			$label = Tr::_( 'Delete', [], Tr::COMMON_NAMESPACE );
		}

		$button = new button_delete( $label );

		return $button;

	}


	/**
	 * @param string $label
	 * @return button_create
	 */
	public static function button_create( $label ) {

		$button = new button_create( $label );

		return $button;

	}

	/**
	 * @param Locale $locale
	 *
	 * @return flag
	 */
	public static function flag( Locale $locale ) {

		$flag = new flag( $locale );

		return $flag;
	}

	/**
	 * @param string $icon
	 *
	 * @return icon
	 */
	public static function icon( $icon ) {
		$icon = new icon( $icon );

		return $icon;
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @param int $width
	 *
	 * @return dialog
	 */
	public static function dialog( $id, $title, $width) {

		$dialog = new dialog( $id, $title, $width );

		return $dialog;
	}

	/**
	 * @param Locale $locale
	 *
	 * @return string
	 */
	public static function locale( Locale $locale ) {

		$res = UI::flag($locale);
		$res .= ' '.$locale->getRegionName().' ('.$locale->getLanguageName().')';

		return $res;

	}

	/**
	 * @param array $tabs
	 *
	 * @return tabs
	 */
	public static function tabs( array $tabs ) {
		return new tabs($tabs);
	}

	/**
	 * @param string $name
	 * @return searchForm
	 */
	public static function searchForm( $name ) {
		return new searchForm($name);
	}
}