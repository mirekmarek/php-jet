<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

trait Application_Module_HasEmailTemplates_Trait
{
	public function getEmailTemplatesDir(): string
	{
		return $this->getModuleManifest()->getModuleDir().'email-templates/';
	}
	
	public static function createEmailTemplate( string $template_id, Locale $locale ): Mailing_Email_Template
	{
		
		/**
		 * @var Application_Module_HasEmailTemplates_Interface $module
		 */
		$module = Application_Modules::moduleInstance( Application_Modules::getModuleNameByClassName( static::class ) );
		
		
		$current = SysConf_Jet_Mailing::getTemplatesDir();
		
		SysConf_Jet_Mailing::setTemplatesDir( $module->getEmailTemplatesDir() );
		
		$template = new Mailing_Email_Template( template_id: $template_id, locale: $locale );
		
		SysConf_Jet_Mailing::setTemplatesDir( $current );
		
		return $template;
	}
}