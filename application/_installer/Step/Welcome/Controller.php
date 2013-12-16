<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

class Installer_Step_Welcome_Controller extends Installer_Step_Controller {



	public function main() {
		$_translations = file(JET_APPLICATION_PATH."_installer/available translations.txt");

		$all_translations = Locale::getAllLocalesList($this->installer->getCurrentLocale());
		$translations = array();
		foreach($_translations as $tr) {
			$tr = trim($tr);

			$translations[$tr] = $all_translations[$tr];
		}


		$select_locale_form = new Form("select_locale_form",
			array(
				Form_Factory::field("Select","locale", "Please select locale: "),
			)
		);
		$select_locale_form->getField("locale")->setSelectOptions( $translations );
		$select_locale_form->getField("locale")->setIsRequired(true);
		$select_locale_form->getField("locale")->setDefaultValue($this->installer->getCurrentLocale());

		if($select_locale_form->catchValues() && $select_locale_form->validateValues()) {
			$d = $select_locale_form->getValues();
			$this->installer->setCurrentLocale(new Locale($d["locale"]));
			$this->installer->goNext();
		}

		$this->view->setVar("form", $select_locale_form);

		$this->render("default");
	}

	public function getLabel() {
		return Tr::_("Welcome", array(), "Welcome");
	}
}
