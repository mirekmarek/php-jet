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
trait Mvc_Page_Trait_Save
{
	/**
	 * @return array
	 */
	public function toArray(): array
	{

		$data = get_object_vars( $this );

		foreach( $data as $k => $v ) {
			if(
				$k == 'content' ||
				$k == 'meta_tags' ||
				$k[0] == '_'
			) {
				unset( $data[$k] );
			}
		}

		unset( $data['relative_path'] );
		unset( $data['parent_id'] );
		unset( $data['children'] );
		unset( $data['base_id'] );
		unset( $data['locale'] );
		unset( $data['relative_path_fragment'] );
		unset( $data['original_relative_path_fragment'] );


		$data['meta_tags'] = [];
		foreach( $this->meta_tags as $meta_tag ) {
			$data['meta_tags'][] = $meta_tag->toArray();
		}

		if(
			!$this->getOutput()
		) {
			unset( $data['output'] );

			$data['contents'] = [];
			foreach( $this->content as $content ) {
				$data['contents'][] = $content->toArray();
			}
		} else {
			unset( $data['layout_script_name'] );
		}


		return $data;
	}


	/**
	 * @return string
	 */
	public function getDataDirPath(): string
	{
		if( !$this->getParent() ) {
			return Mvc_Base::get( $this->base_id )->getPagesDataPath( $this->locale );
		} else {
			return $this->getParent()->getDataDirPath() . rawurldecode( $this->relative_path_fragment ) . '/';
		}
	}

	/**
	 * @return string
	 */
	public function getOriginalDataDirPath(): string
	{
		if( !$this->getParent() ) {
			return Mvc_Base::get( $this->base_id )->getPagesDataPath( $this->locale );
		} else {
			return $this->getParent()->getDataDirPath() . rawurldecode( $this->original_relative_path_fragment ) . '/';
		}
	}

	/**
	 *
	 */
	public function saveDataFile(): void
	{
		if(
			$this->original_relative_path_fragment &&
			$this->relative_path_fragment != $this->original_relative_path_fragment
		) {

			$page_dir = $this->getDataDirPath();
			$original_page_dir = $this->getOriginalDataDirPath();

			IO_Dir::rename( $original_page_dir, $page_dir );
		}

		$data = $this->toArray();

		$page_dir = $this->getDataDirPath();

		$data_file_path = $page_dir . SysConf_Jet_Mvc::getPageDataFileName();

		IO_File::write(
			$data_file_path,
			'<?php' . PHP_EOL . 'return ' . (new Data_Array( $data ))->export()
		);

		Mvc_Cache::reset();
	}

}