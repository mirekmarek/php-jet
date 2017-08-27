<?php
use Jet\Mvc_Layout;

/**
 * @var Mvc_Layout $this
 */

$this->requireMainCssFile( BOOTSTRAP_CSS_URL );
$this->requireMainCssFile( FONT_AWESOME_CSS_URL );
$this->requireMainCssFile( FLAGS_CSS_URL );
$this->requireMainCssFile( 'styles/site_main.css?v=1' );


$this->requireMainJavascriptFile( JQUERY_JS_URL );
$this->requireMainJavascriptFile( JET_AJAX_FORM_JS_URL );

