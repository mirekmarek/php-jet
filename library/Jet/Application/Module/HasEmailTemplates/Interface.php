<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

interface Application_Module_HasEmailTemplates_Interface
{
	public function getEmailTemplatesDir(): string;
	
	public static function createEmailTemplate( string $template_id, Locale $locale ): Mailing_Email_Template;
}