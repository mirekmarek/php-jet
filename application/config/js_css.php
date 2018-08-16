<?php
const BOOTSTRAP_CSS_URL = '//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css';
const FONT_AWESOME_CSS_URL = '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
const FLAGS_CSS_URL = JET_URI_PUBLIC.'styles/flags.css';

const BOOTSTRAP_JS_URL = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js';
const JQUERY_JS_URL = '//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js';

const JET_AJAX_FORM_JS_URL = JET_URI_PUBLIC.'scripts/JetAjaxForm.js?v=1';



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
		.'</script>'.JET_EOL;
};



/*
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

	'content_css' => JET_URI_PUBLIC.'styles/wysiwyg.css',
];

$GLOBALS['WYSIWYG_DEFAULT_INITIALIZER_GENERATOR'] = function( \Jet\Form_Field_WYSIWYG $field, $editor_config ) {

	$editor_config['selector'] = '#'.$field->getId();

	if( $field->getIsReadonly() ) {
		$editor_config['readonly'] = 1;
	}

	return '<script type="text/javascript">'
		.'tinymce.init('.json_encode( $editor_config ).');'
		.'</script>'.JET_EOL;

};
*/