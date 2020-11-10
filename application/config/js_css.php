<?php
use Jet\Form_Field_WYSIWYG;
use Jet\SysConf_URI;
use Jet\SysConf_Jet;

const JQUERY_JS_URL = 'https://code.jquery.com/jquery-3.5.1.js';

const BOOTSTRAP_CSS_URL = 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css';
const BOOTSTRAP_JS_URL  = 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js';

const FONT_AWESOME_CSS_URL = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css';

define('FLAGS_CSS_URL', SysConf_URI::PUBLIC().'styles/flags.css');

define('JET_AJAX_FORM_JS_URL', SysConf_URI::PUBLIC().'scripts/JetAjaxForm.js?v=1');
define('JET_MULTI_UPLOADER_JS_URL', SysConf_URI::PUBLIC().'scripts/JetMultiUploader.js?v=1');


const WYSIWYG_DEFAULT_EDITOR_CSS_FILES = [
	'' => [
		'//cdn.tinymce.com/4/skins/lightgray/skin.min.css'
	],
];

const WYSIWYG_DEFAULT_EDITOR_JAVASCRIPT_FILES = [
	'//cdn.tinymce.com/4/tinymce.min.js',
];

const WYSIWYG_DEFAULT_EDITOR_CONFIG = [
	'mode'                    => 'exact',
	'theme'                   => 'modern',
	'skin'                    => false,
	'apply_source_formatting' => true,
	'remove_linebreaks'       => false,
	'entity_encoding'         => 'raw',
	'convert_urls'            => false,
	'verify_html'             => true,

	'force_br_newlines' => false,
	'force_p_newlines'  => false,
	'forced_root_block' => '',


	'plugins'       => 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality template paste textcolor colorpicker textpattern imagetools',
	'paste_as_text' => true,

	'content_css' => '/public/styles/wysiwyg.css',
];

$GLOBALS['WYSIWYG_DEFAULT_INITIALIZER_GENERATOR'] = function( Form_Field_WYSIWYG $field, $editor_config ) {

	$editor_config['selector'] = '#'.$field->getId();

	if( $field->getIsReadonly() ) {
		$editor_config['readonly'] = 1;
	}

	return '<script type="text/javascript">'
		.'tinymce.init('.json_encode( $editor_config ).');'
		.'</script>'.SysConf_Jet::EOL();

};


/*
const WYSIWYG_DEFAULT_EDITOR_CSS_FILES = [
	'' => [
		'//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css'
	],
];
const WYSIWYG_DEFAULT_EDITOR_JAVASCRIPT_FILES = [
	BOOTSTRAP_JS_URL,
	'//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js'
];
const WYSIWYG_DEFAULT_EDITOR_CONFIG = [
	'height' => 500,
];

$GLOBALS['WYSIWYG_DEFAULT_INITIALIZER_GENERATOR'] = function( \Jet\Form_Field_WYSIWYG $field, $editor_config ) {
	return '<script type="text/javascript">'
		.'$("#'.$field->getId().'").summernote('.json_encode($editor_config).');'
		.'</script>'.SysConf_Jet::EOL();
};
*/
