<?php

use Jet\MVC_Layout;
use Jet\SysConf_URI;

/**
 * @var MVC_Layout $this
 */


$this->requireMainCssFile( 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css' );
$this->requireMainCssFile( 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css' );
$this->requireMainCssFile( SysConf_URI::getCss() . 'flags.css' );
$this->requireMainCssFile( SysConf_URI::getCss() . 'admin_main.css?v=1' );

$this->requireMainJavascriptFile( 'https://code.jquery.com/jquery-3.5.1.js' );
$this->requireMainJavascriptFile( 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js' );
$this->requireMainJavascriptFile( SysConf_URI::getJs() . 'JetAjaxForm.js?v=1' );