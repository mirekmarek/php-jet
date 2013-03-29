<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Exception
 */
namespace Jet;

class Translator_Exception extends Exception {

	const CODE_IMPORT_INCORRECT_DICTIONARY_EXPORT_FILE_FORMAT = 1000;

	const CODE_BACKEND_ERROR = 10000;

}