<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait MVC_Page_Trait_Save
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
	 *
	 */
	public function saveDataFile(): void
	{
		if(!$this->getDataFilePath()) {
			return;
		}

		$curr_data_dir_path = $this->getDataDirPath( true );
		$old_data_dir_path = $this->getDataDirPath();

		if( $curr_data_dir_path != $old_data_dir_path ) {
			IO_Dir::rename( $old_data_dir_path, $curr_data_dir_path );

			$this->setDataFilePath( $this->getDataFilePath( true ) );
		}

		$data = $this->toArray();

		IO_File::writeDataAsPhp( $this->getDataFilePath(), $data );

		MVC_Cache::reset();
	}

}