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

class Installer_Step_CreateSite_Controller extends Installer_Step_Controller {


	public function main() {
		/** @noinspection PhpUndefinedMethodInspection */
		if(Mvc_Sites::getAllSitesList()->getCount()) {
			$this->render("site-created");
			if(Http_Request::POST()->exists("go")) {
				$this->installer->goNext();
			}

			return;
		}

		if(
			Http_Request::GET()->exists("create") &&
			isset($_SESSION["cw_data"])
		) {
			$data = $_SESSION["cw_data"];
			$site_data = Mvc_Sites::getNewSite($data["name"], $data["ID"]);

			foreach($data["locales"] as $locale=>$locale_data) {
				$locale = new Locale($locale);
				$site_data->addLocale( $locale );

				$site_data->addURL($locale, $locale_data["nonSSL"]);
				$site_data->addURL($locale, $locale_data["SSL"]);
			}

			ob_start();
			Mvc_Sites::createSite($site_data, $data["template"], true);
			ob_end_clean();

			$_SESSION["cw_created"] = true;

			Http_Headers::movedPermanently("?");
		}

		$default_locale = $this->installer->getCurrentLocale();

		$all_locales = Locale::getAllLocalesList($default_locale);
		$avl_locales = $all_locales;

		if(!isset($_SESSION["cw_selected_locale"])) {
			$_SESSION["cw_selected_locale"] = array();
		}

		if(Http_Request::GET()->exists("remove_locale")) {
			$remove_locale = Http_Request::GET()->getString("remove_locale");
			if(isset($_SESSION["cw_selected_locale"][$remove_locale])) {
				unset($_SESSION["cw_selected_locale"][$remove_locale]);
			}
			Http_Headers::movedPermanently("?");
		}


		foreach(array_keys($_SESSION["cw_selected_locale"]) as $s_locale) {
			unset($avl_locales[$s_locale]);
		}

		$add_locale_form = new Form("locale_add",
			array(
				Form_Factory::field("Select","locale", "Select new locale: "),
			)
		);

		$add_locale_form->getField("locale")->setSelectOptions( $avl_locales );
		$add_locale_form->getField("locale")->setIsRequired(true);

		if($add_locale_form->catchValues() && $add_locale_form->validateValues()) {
			$d = $add_locale_form->getValues();
			$locale = $d["locale"];

			$nonSSL = "http://".$_SERVER["HTTP_HOST"].JET_BASE_URI;
			$SSL = "https://".$_SERVER["HTTP_HOST"].JET_BASE_URI;

			if($_SESSION["cw_selected_locale"]) {
				$nonSSL.= $locale."/";
				$SSL.= $locale."/";
			}

			$_SESSION["cw_selected_locale"][$locale] = array(
				"locale" => $all_locales[$locale],
				"nonSSL" => $nonSSL,
				"SSL" => $SSL
			);
			Http_Headers::formSent($add_locale_form);
		}

		$URL_fields = array(
			Form_Factory::field("Input","name", "Site name: ", "", true),
			Form_Factory::field("Input","ID", "Site ID: ", "", false),
			Form_Factory::field("Select","template", "Site template: ", "", true),
		);

		foreach($_SESSION["cw_selected_locale"] as $locale=>$dat) {
			$URL_fields[] = Form_Factory::field("Input","/{$locale}/nonSSL", "URL: ", $dat["nonSSL"], true);
			$URL_fields[] = Form_Factory::field("Input","/{$locale}/SSL", "SSL URL: ", $dat["SSL"], false);
		}

		$main_form = new Form("main", $URL_fields );

		$templates_list = Mvc_Sites::getAvailableTemplatesList();

		$main_form->getField("template")->setSelectOptions($templates_list);

		if(
			$main_form->catchValues() &&
			$main_form->validateValues()
		) {
			$data = $main_form->getValues();
			$_SESSION["cw_data"] = array(
				"name" => $data["name"],
				"ID" => $data["ID"],
				"template" => $data["template"],
				"locales" => array()
			);

			foreach($_SESSION["cw_selected_locale"] as $locale=>$dat) {
				$_SESSION["cw_data"]["locales"][$locale] = array(
					"nonSSL" => $data["/{$locale}/nonSSL"],
					"SSL" => $data["/{$locale}/SSL"],
				);
			}

			$this->render("in-progress");

			return;
		}

		//$main_form->helper_showBasicHTML();


		$this->view->setVar("add_locale_form", $add_locale_form);
		$this->view->setVar("main_form", $main_form);
		$this->view->setVar("locales", $_SESSION["cw_selected_locale"] );

		$this->render("default");
	}

	public function getLabel() {
		return Tr::_("Create site", array(), "CreateSite");
	}
}
