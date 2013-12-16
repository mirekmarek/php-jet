<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 */
namespace Jet;

class Javascript extends Object {

	/**
	 * @static
	 * @param array $path_fragments
	 * @param bool $get_as_string
	 * @return string
	 * @throws Javascript_Exception
	 *
	 */
	public static function handleJetJSRequest( array $path_fragments, $get_as_string=false){
		if(count($path_fragments)<1) {
			throw new Javascript_Exception(
				"Invalid path (empty)",
				Javascript_Exception::CODE_INVALID_JS_LIB_PATH
			);
		}

		$group = array_shift($path_fragments);

		if($group!="Jet" && $group!="Jet.js" && $group!="modules") {
			throw new Javascript_Exception(
				"Unknown JetJS group: '{$group}'. Valid options: 'Jet', 'Jet.js', 'modules'",
				Javascript_Exception::CODE_JS_NOT_FOUND
			);
		}

		foreach($path_fragments as $pf) {
			if(strpos($pf, "..")!==false) {
				throw new Javascript_Exception(
					"Invalid path (contains '..')",
					Javascript_Exception::CODE_INVALID_JS_LIB_PATH
				);
			}
		}

		$JS_file_path = null;

		if($group=="Jet.js") {
			$JS_file_path = JET_PUBLIC_SCRIPTS_PATH."Jet/Jet.js";
		} else
		if($group=="Jet") {
			$JS_file_path = JET_PUBLIC_SCRIPTS_PATH."Jet/Jet/".implode("/",$path_fragments);
		} else
		if($group=="modules") {
			$module_name = str_replace(".", "\\", array_shift($path_fragments));

			Application_Modules::getModuleInstance($module_name);
			Translator::setCurrentNamespace( $module_name );

			if(!$path_fragments) {
				$path_fragments[] = "Main";
			}


			$JS_file_path = JET_APPLICATION_MODULES_PATH.str_replace("\\", "/", $module_name)."/JS/".implode("/", $path_fragments).".js";
		}

		if(!IO_File::exists($JS_file_path)){
			throw new Javascript_Exception(
				"Javascript '{$JS_file_path}' not found",
				Javascript_Exception::CODE_JS_NOT_FOUND
			);
		}

		$JS = IO_File::read($JS_file_path);



		preg_match_all("~Jet.translate\(('.*'|\".*\")\)~isU", $JS, $matches, PREG_SET_ORDER);

		$replacements = array();
		foreach($matches as $match){
			list($search, $text) = $match;
			$text = stripslashes(trim($text, $text[0] == "'" ? "'" : '"'));

			$text = json_encode(Tr::_($text));
			$JS = str_replace($search, $text, $JS);
		}
		$JS = Data_Text::replaceData($JS, $replacements, true);


		if($get_as_string){
			return $JS;
		}

		Http_Headers::responseOK(
			array(
				"Content-type" => "text/javascript;charset=utf-8"
			)
		);

		echo $JS;

		Application::end();

		return null;
	}
}