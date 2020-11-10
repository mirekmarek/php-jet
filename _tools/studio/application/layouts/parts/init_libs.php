<?php
use Jet\Mvc_Layout;
use Jet\SysConf_URI;

/**
 * @var Mvc_Layout $this
 */


$this->requireMainCssFile( BOOTSTRAP_CSS_URL );
$this->requireMainCssFile( FONT_AWESOME_CSS_URL );
$this->requireMainCssFile( FLAGS_CSS_URL );
$this->requireMainCssFile( JQUERY_UI_CSS_URL );
$this->requireMainCssFile( SysConf_URI::PUBLIC().'styles/main.css?v=8' );

$this->requireMainJavascriptFile( JQUERY_JS_URL );
$this->requireMainJavascriptFile( JQUERY_UI_JS_URL );
$this->requireMainJavascriptFile( 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js' );
$this->requireMainJavascriptFile( BOOTSTRAP_JS_URL );
$this->requireMainJavascriptFile( JET_AJAX_FORM_JS_URL );



