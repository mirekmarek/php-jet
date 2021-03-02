<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Mvc_Layout extends Mvc_View_Abstract
{
	const TAG_POSITION = 'jet_layout_position';
	const TAG_MAIN_POSITION = 'jet_layout_main_position';

	const TAG_JAVASCRIPT = 'jet_layout_javascripts';
	const TAG_CSS = 'jet_layout_css';

	const TAG_META_TAGS = 'jet_layout_meta_tags';

	const TAG_MODULE = 'jet_module';


	const DEFAULT_OUTPUT_POSITION = '__main__';

	/**
	 * @var Mvc_Layout|null
	 */
	protected static Mvc_Layout|null $current_layout = null;

	/**
	 * Data of the output that will be placed into the layout
	 *
	 * @var Mvc_Layout_OutputPart[]
	 */
	protected array $output_parts = [];

	/**
	 * @var array
	 */
	protected array $virtual_positions = [];


	/**
	 * @var array
	 */
	protected array $required_main_javascript_files = [];

	/**
	 * @var array
	 */
	protected array $required_javascript_files = [];

	/**
	 * @var string[][]
	 */
	protected array $required_main_css_files = [];

	/**
	 * @var string[][]
	 */
	protected array $required_css_files = [];

	/**
	 * @var bool
	 */
	protected bool $JS_packager_enabled = true;

	/**
	 * @var bool
	 */
	protected bool $CSS_packager_enabled = true;

	/**
	 * @return Mvc_Layout|null
	 */
	public static function getCurrentLayout(): Mvc_Layout|null
	{
		return static::$current_layout;
	}

	/**
	 * @param Mvc_Layout $current_layout
	 */
	public static function setCurrentLayout( Mvc_Layout $current_layout ): void
	{
		static::$current_layout = $current_layout;
	}

	/**
	 *
	 * @param string $scripts_dir
	 * @param string $script_name
	 */
	public function __construct( string $scripts_dir, string $script_name )
	{
		$this->setScriptsDir( $scripts_dir );
		$this->setScriptName( $script_name );

		$this->JS_packager_enabled = SysConf_Jet::isJSPackagerEnabled();
		$this->CSS_packager_enabled = SysConf_Jet::isCSSPackagerEnabled();

		$this->_data = new Data_Array();
	}


	/**
	 * @return string
	 *
	 */
	protected function _render(): string
	{
		if( $this->_script_name === false ) {
			$result = '<' . static::TAG_MAIN_POSITION . '/>';
		} else {
			$this->getScriptPath();

			ob_start();

			/** @noinspection PhpIncludeInspection */
			include $this->_script_path;

			if( static::getAddScriptPathInfoEnabled() ) {
				echo '<!-- LAYOUT: ' . $this->_script_name . ' -->';
			}

			$result = ob_get_clean();
		}

		return $result;
	}


	/**
	 * Adds output to specified position
	 *
	 * @param string $output
	 * @param string|null $position (optional, default: main position)
	 * @param int|null $position_order (optional, default:null)
	 */
	public function addOutputPart( string $output, string|null $position = null, int|null $position_order = null ): void
	{
		if( !$position ) {
			$position = static::DEFAULT_OUTPUT_POSITION;
		}

		if(
			$position_order === null ||
			$position_order === false
		) {
			$position_order = 0;
			foreach( $this->output_parts as $o ) {
				if( $o->getPosition() !== $position ) {
					continue;
				}

				if( $o->getPositionOrder() >= $position_order ) {
					$position_order = $o->getPositionOrder() + 1;
				}

			}
		}

		$current_max_position_order = null;
		foreach( $this->output_parts as $output_part ) {
			$_po = $output_part->getPositionOrder();

			if( floor( $_po ) == floor( $position_order ) ) {
				if( $_po > $current_max_position_order ) {
					$current_max_position_order = $_po;
				}
			}
		}

		if( $current_max_position_order !== null ) {
			$position_order = $current_max_position_order + 0.001;
		}


		$o = new Mvc_Layout_OutputPart( $output, $position, $position_order );

		$this->output_parts[] = $o;
	}


	/**
	 * @return bool
	 */
	public function getJSPackagerEnabled(): bool
	{
		return $this->JS_packager_enabled;
	}

	/**
	 * @param bool $JS_packager_enabled
	 */
	public function setJSPackagerEnabled( bool $JS_packager_enabled ): void
	{
		$this->JS_packager_enabled = (bool)$JS_packager_enabled;
	}

	/**
	 * @param string $URI
	 */
	public function requireJavascriptFile( string $URI ): void
	{
		if( !in_array( $URI, $this->required_javascript_files ) ) {
			$this->required_javascript_files[] = $URI;
		}
	}

	/**
	 * @param string $URI
	 */
	public function requireMainJavascriptFile( string $URI ): void
	{
		if( !in_array( $URI, $this->required_main_javascript_files ) ) {
			$this->required_main_javascript_files[] = $URI;
		}
	}


	/**
	 * @return bool
	 */
	public function getCSSPackagerEnabled(): bool
	{
		return $this->CSS_packager_enabled;
	}

	/**
	 * @param bool $CSS_packager_enabled
	 */
	public function setCSSPackagerEnabled( bool $CSS_packager_enabled ): void
	{
		$this->CSS_packager_enabled = (bool)$CSS_packager_enabled;
	}

	/**
	 * @param string $URI
	 * @param string $media (optional)
	 */
	public function requireCssFile( string $URI, string $media = '' ): void
	{

		if( !isset( $this->required_css_files[$media] ) ) {
			$this->required_css_files[$media] = [];
		}

		if( !in_array( $URI, $this->required_css_files[$media] ) ) {
			$this->required_css_files[$media][] = $URI;
		}

	}

	/**
	 * @param string $URI
	 * @param string $media (optional)
	 */
	public function requireMainCssFile( string $URI, string $media = '' ): void
	{

		if( !isset( $this->required_main_css_files[$media] ) ) {
			$this->required_main_css_files[$media] = [];
		}

		if( !in_array( $URI, $this->required_main_css_files[$media] ) ) {
			$this->required_main_css_files[$media][] = $URI;
		}

	}

	/**
	 *
	 *
	 * @return string
	 */
	public function render(): string
	{

		$result = $this->_render();

		$this->handleContent( $result );

		$this->handlePositions( $result );

		$this->handleMetaTags( $result );


		$this->handleJavascripts( $result );
		$this->handleCss( $result );

		$this->handlePostprocessors( $result );

		$this->output_parts = [];

		return $result;
	}

	/**
	 * @param string $result
	 */
	protected function handleContent( string $result ): void
	{

		$content = $this->parseContent( $result );

		foreach( $content as $c ) {
			$c->dispatch();
		}

	}

	/**
	 * @param string $result
	 *
	 * @return Mvc_Page_Content_Interface[]
	 */
	public function parseContent( string $result ): array
	{

		$matches = [];
		if( !preg_match_all( '/<' . static::TAG_MODULE . '([^>]*)>/i', $result, $matches, PREG_SET_ORDER ) ) {
			return [];
		}

		$content = [];

		foreach( $matches as $match ) {
			$orig_str = $match[0];

			$_properties = substr( trim( $match[1] ), 0, -1 );
			$_properties = preg_replace( '/[ ]{2,}/i', ' ', $_properties );
			$_properties = explode( '" ', $_properties );


			$properties = [];


			foreach( $_properties as $property ) {
				if( !$property || !str_contains( $property, '=' ) ) {
					continue;
				}

				$property = explode( '=', $property );

				$property_name = array_shift( $property );
				$property_value = implode( '=', $property );

				$property_name = strtolower( $property_name );
				$property_value = str_replace( '"', '', $property_value );

				$properties[$property_name] = $property_value;

			}


			$module_name = $properties['module'];
			$action = isset( $properties['action'] ) ? $properties['action'] : '';
			$parameters = [];
			$is_cacheable = strtolower( isset( $properties['is_cacheable'] ) ? $properties['is_cacheable'] : false ) == 'true';

			foreach( $properties as $k => $v ) {
				if(
					$k == 'module' ||
					$k == 'action' ||
					$k == 'is_cacheable'
				) {
					continue;
				}

				$parameters[$k] = $v;
			}

			$position_name = 'module_content_' . md5( $orig_str );

			$this->virtual_positions[$orig_str] = $position_name;

			$page_content = Mvc_Factory::getPageContentInstance();

			$page_content->setModuleName( $module_name );
			$page_content->setControllerAction( $action );
			$page_content->setParameters( $parameters );
			$page_content->setOutputPosition( $position_name );
			$page_content->setOutputPositionOrder( 1 );
			$page_content->setIsCacheable( $is_cacheable );

			$content[] = $page_content;
		}

		return $content;
	}

	/**
	 * @param string &$result
	 */
	protected function handlePositions( string &$result ): void
	{
		foreach( $this->virtual_positions as $original_string => $position ) {
			$result = str_replace( $original_string, '<' . static::TAG_POSITION . ' name="' . $position . '" />', $result );
		}

		uasort( $this->output_parts, function( Mvc_Layout_OutputPart $a, Mvc_Layout_OutputPart $b ) {
			$a_o = $a->getPositionOrder();
			$b_o = $b->getPositionOrder();

			if( $a_o == $b_o ) {
				return 0;
			}
			return ($a_o < $b_o) ? -1 : 1;
		} );


		$this->_handlePositions( $result, true );

	}

	/**
	 * Place the output to an adequate position
	 *
	 * @param string &$result
	 * @param bool $handle_main_position
	 *
	 * @return int
	 */
	protected function _handlePositions( string &$result, bool $handle_main_position ): int
	{

		$matches_count = 0;
		$matches = [];

		if( preg_match_all(
			'/<' . static::TAG_POSITION . '[ ]+name="([a-zA-Z0-9\-_ ]*)"[^\/]*\/>/i',
			$result,
			$matches,
			PREG_SET_ORDER
		) ) {

			$matches_count = $matches_count + count( $matches );

			foreach( $matches as $match ) {
				$orig = $match[0];
				$position = $match[1];

				$output_on_position = '';

				foreach( $this->output_parts as $o_id => $o ) {
					if( $o->getPosition() != $position ) {
						continue;
					}

					$output_on_position .= $o->getOutput();
					unset( $this->output_parts[$o_id] );
				}

				$result = str_replace( $orig, $output_on_position, $result );

			}
		}


		if(
			$handle_main_position &&
			preg_match_all(
				'/<' . static::TAG_MAIN_POSITION . '[^\/]*\/>/i',
				$result,
				$matches,
				PREG_SET_ORDER
			)
		) {
			$orig = $matches[0][0];
			$output_on_position = '';

			foreach( $this->output_parts as $o_id => $o ) {
				if( $o->getPosition() == static::DEFAULT_OUTPUT_POSITION ) {
					$output_on_position .= $o->getOutput();
					unset( $this->output_parts[$o_id] );
				}
			}

			$result = str_replace( $orig, $output_on_position, $result );
		}

		return $matches_count;

	}

	/**
	 * @param string &$result
	 */
	protected function handleMetaTags( string &$result ): void
	{
		$dat = [];
		$dat[static::TAG_META_TAGS] = '';

		if( ($page = Mvc::getCurrentPage()) ) {

			foreach( $page->getMetaTags() as $mt ) {
				$dat[static::TAG_META_TAGS] .= PHP_EOL . "\t" . $mt;
			}

		}

		foreach( $dat as $tag => $rep_l ) {
			$result = $this->_replaceTagByValue( $result, $tag, $rep_l );
		}

	}

	/**
	 * @param string $output
	 * @param string $tag
	 * @param string $snippet
	 *
	 * @return string
	 */
	protected function _replaceTagByValue( string $output, string $tag, string $snippet ): string
	{
		$matches = [];

		if( preg_match_all( '/<[ ]*' . $tag . '[ ]*\/>/i', $output, $matches, PREG_SET_ORDER ) ) {
			$orig = $matches[0][0];


			$output = str_replace( $orig, $snippet, $output );
		}

		if( preg_match_all( '/<[ ]*' . $tag . '[ ]*>*<\/[ ]*' . $tag . '[ ]*>/i', $output, $matches, PREG_SET_ORDER ) ) {
			$orig = $matches[0][0];


			$output = str_replace( $orig, $snippet, $output );
		}

		return $output;

	}

	/**
	 *
	 * @param string &$result
	 */
	protected function handleJavascripts( string &$result ): void
	{

		if( !strpos( $result, static::TAG_JAVASCRIPT ) ) {
			return;
		}


		$JS_files = array_unique(
			array_merge(
				$this->required_main_javascript_files,
				$this->required_javascript_files
			)
		);


		if(
			$this->JS_packager_enabled &&
			$JS_files
		) {
			$package_creator = PackageCreator::JavaScript( $JS_files );

			$package_creator->generate();
			$package_URI = $package_creator->getPackageURI();

			$JS_files = [$package_URI];
		}


		$snippet = '';

		foreach( $JS_files as $URI ) {
			$snippet .= "\t" . '<script type="text/javascript" src="' . $URI . '"></script>' . PHP_EOL;
		}

		$result = $this->_replaceTagByValue( $result, static::TAG_JAVASCRIPT, $snippet );

	}

	/**
	 * Handle the CSS tag  ( <jet_layout_css/> )
	 *
	 * @param string &$result
	 * @see Mvc_Layout::requireCssFile();
	 *
	 */
	protected function handleCss( string &$result ): void
	{

		if( !strpos( $result, static::TAG_CSS ) ) {
			return;
		}

		$CSS_files = $this->required_main_css_files;

		foreach( $this->required_css_files as $media => $files ) {
			if( !isset( $CSS_files[$media] ) ) {
				$CSS_files[$media] = $files;

				continue;
			}

			foreach( $files as $file ) {
				if( in_array( $file, $CSS_files[$media] ) ) {
					continue;
				}

				$CSS_files[$media][] = $file;
			}
		}


		if(
			$this->CSS_packager_enabled &&
			$CSS_files
		) {

			foreach( $CSS_files as $media => $URIs ) {

				$package_creator = PackageCreator::CSS(
					$media,
					$URIs
				);

				$package_creator->generate();
				$package_URI = $package_creator->getPackageURI();

				$CSS_files[$media] = [$package_URI];
			}
		}

		$snippet = '';
		foreach( $CSS_files as $media => $URIs ) {
			/**
			 * @var array $URIs
			 */
			foreach( $URIs as $URI ) {

				if( $media ) {
					$snippet .= "\t" . '<link rel="stylesheet" type="text/css" href="' . $URI . '" media="' . $media . '"/>' . PHP_EOL;
				} else {
					$snippet .= "\t" . '<link rel="stylesheet" type="text/css" href="' . $URI . '"/>' . PHP_EOL;
				}

			}
		}

		$result = $this->_replaceTagByValue( $result, static::TAG_CSS, $snippet );


	}

}