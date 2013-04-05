<?php
/**
 * @var JetApplicationModule\Vendor\TestModule\Main $module
 */
$module = Jet\Application_Modules::getModuleInstance("Vendor\\Package\\TestModule");
$module->uninstall();
